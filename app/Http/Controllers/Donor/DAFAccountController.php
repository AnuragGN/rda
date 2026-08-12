<?php

namespace App\Http\Controllers\Donor;

use App\Forms\FormInvestments;
use App\Helpers\Data;
use App\Http\Controllers\Controller;
use App\Models\ClientInfo;
use App\Models\Config;
use App\Models\Contact;
use App\Models\ClientConfig;
use App\Models\DAF\DAFDonor;
use App\Models\DAF\DAFSecurity;
use App\Models\DAF\DAFStocks;
use App\Models\DAF\DAFSuccessorIndividuals;
use App\Models\DAF\DAFSuccessorOrganizations;
use App\Models\DAFAccount;
use App\Models\EmailAddress;
use App\Models\PasswordLink;
use App\Models\User;
use App\Models\DAF\DAFAdditionalDonor;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Traits\AuthTrait;
use App\Models\Email;
use App\Helpers\GConst;
use App\Forms\FormDAFPayTo;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use App\Rules\newPassword;

class DAFAccountController extends Controller
{
    // for password encryption
    use AuthTrait;

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index($id=null)
    {
        if (!Config::enableDafAppNewDonor()) {
            return abort(403);
        }

        /** @var DAFAccount $daf */
        $daf = DAFAccount::getDAFAccount($id);

        if ($daf->isSubmitted()) {
            return redirect(route('daf-application-status', $daf->id));
        } else if ($daf->isIncomplete()) {
            return redirect(route('daf-account-info', $daf->id));
        } else {
            return abort(403);
        }
    }

    public function formAccount()
    {
        $model = new Contact();
        return view('donor.registration.form-account', compact('model'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */
    public function saveAccount(Request $request)
    {
        // input validations
        $request->validate(['email' => 'email:rfc,dns']);
        $rules = [
            "first_name" => "required|min:1|max:32",
            "last_name" => "required|min:1|max:32",
            'email' => 'required|email',
            //'password' => 'required|confirmed|min:6',
            'password' => ['required', 'string', 'max:32', 'confirmed', new newPassword()]
        ];
         //$rules['password'] = ['required', 'string', 'max:32', 'confirmed', new newPassword()];

        $request->validate($rules, []);

        // check is email already exists
        $email = $request->email;
        $password = $request->input('password');

        // make sure the email is not already in use
        $userExists = User::where(['username' => $email])->exists();
        $contactExists = EmailAddress::where(['email_address' => $email])->exists();
        if ($userExists || $contactExists) {
            throw ValidationException::withMessages(['email' => ['This email is already in use.']]);
        }

        // create auth user
        $user = new User();
        $user->active = 'N'; // TODO: Must be 'N', change it to 'Y' on account activation!
        $user->being_reviewed = 'Y'; // TODO: change it to 'N' on DAF Approval
        $user->username = $email;
        $user->password = $this->encrypt($password);
        $user->created_on = date('Y-m-d H:i:s');
        $user->modified_on = date('Y-m-d H:i:s');
        $user->has_changed_password = "Y";
        $user->save();

        $registrationType = "self_register";
        $contact = DAFAccount::createDAFContact($request, $registrationType, $user['auth_user_id']);

        $user->contact_id = $contact->contact_id;
        $model = DAFAccount::createDAFAccountForUser($request, $user);

        if ($model) {
            // TODO: EMAIL CONFIRMATION => SEND DAF ACTIVATION MAIL
            /** @var PasswordLink $model */
            $model = new PasswordLink();
            $link = $model->generatePasswordLink($user, $dafActivateLink = true);

            Email::dafRegistration($link, $user);
        }

        return view('donor.registration.account-created', compact('email'));
    }

    /**
     * @param $token
     * @return $this
     */
    public function activateDafAccount($token)
    {
        // get user
        $user = PasswordLink::getLinkUser($token);
        if (is_null($user)) {
            return redirect()->route('login')->with('danger', GConst::M_REGISTER_DAF_BAD_LINK);
        }

        $dafUser = User::where('auth_user_id', $user->auth_user_id);
        $dafUser->update([
            'active' => "Y",
            'modified_on' => date('Y-m-d H:i:s'),
        ]);

        if ($dafUser) {
            return view('donor.registration.account-activated');
        } else {
            return redirect()->route('login')->with('error', 'Account activation failed');
        }
    }

    /**
     * @param Request $request
     * @param $auth_user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getAccountActivationLink(Request $request, $auth_user_id)
    {
        return view('donor.registration.resend-activation-link', compact('auth_user_id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function resendAccountActivationLink(Request $request)
    {
        $auth_user_id = $request->auth_user_id;
        $user = User::getById($request->auth_user_id);
        $link = PasswordLink::resendAccountActivationLink($auth_user_id);

        Email::dafRegistration($link, $user);
        return redirect()->back()->with('success', 'The link has been sent.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formDonorInfo($id=null)
    {
        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_DONOR, $id);
        if (!$dafInfo) abort(404);

        $user = $dafInfo['user'];
        $model = $dafInfo['donor'];

        $id = $dafInfo['daf_account_id'];

        // set email
        if (empty($model->email)) {
            $model->email = User::getSessionUserEmail();
        }

        $personFields = Data::getDonorInfoCustomFields();
        return view('donor.registration.form-account-info', compact('model', 'dafInfo', 'user', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveDonorInfo(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) $request->merge([
            'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
        ]);

        $model = new DAFDonor();
        $request->validate($model->rules(), []);

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_DONOR, $id);
        return $request->save_next ?
            redirect(route('daf-additional-donor', $id))->with('success', 'Information has been saved') :
            redirect(route('daf-account-info', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_ADDITIONAL_DONOR, $id);

        $user = $dafInfo['user'];
        $donors = $dafInfo['donors'];
        $donorInfo = [];

        if ($request->key) {
            // $additionalDonorsList =  DAFAccount::getAdditionalDonorsList();
            foreach ($donors as $donor => $model) {
                if ($model['key'] === $request->key) {
                    $donorInfo = $model;
                }
            }
            if ($donorInfo == null) {
                return redirect()->back()->with('danger', 'Additional donor not found');
            }
        } else {
            $donorInfo = new DAFAdditionalDonor();
        }

        $personFields = Data::getAdditionalDonorInfoCustomFields();
        return view('donor.registration.form-additional-donor', compact('dafInfo', 'user', 'donorInfo', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function saveAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) {
            $request->merge([
                'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
            ]);
        }

        $model = new DAFAdditionalDonor();
        $request->validate($model->rules(), []);

        $maxAdditionalDonors = ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR');

        if ($request->isNew && (DAFAccount::getAdditionalDonorCount($id) >= $maxAdditionalDonors) ) {
            return redirect()->back()->with('message', 'You can not add more.');
        }

        if ($request->isNew == true && $request->contact_id == null) {

            $contactExists = EmailAddress::where(['email_address' => $request->email, 'is_primary' => "Y"])->exists();
            if ($contactExists) {
                throw ValidationException::withMessages(['email' => ['This email is already in use.']]);
            }

            $registrationType = "advisor";
            $contact = DAFAccount::createDAFContact($request, $registrationType);

            $request['contact_id'] = $contact->contact_id;
        }

        if ($request->isNew != true && $request->contact_id != null)
        {
            $contactEmail = EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => "Y"])->first();

            if ($contactEmail['email_address'] === $request->email)
            {
                Contact::where('contact_id', $request->contact_id)->update([
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                ]);
            } else {
                $contactExists = EmailAddress::where(['email_address' => $request->email, 'is_primary' => "Y"])->exists();
                if ($contactExists) {
                    throw ValidationException::withMessages(['email' => ['This email is already in use.']]);
                }

                Contact::where('contact_id', $request->contact_id)->update([
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                ]);
                EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => "Y"])->update([
                    "email_address" => $request->email,
                ]);
            }
        }

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_ADDITIONAL_DONOR, $id);

        if ($request->save_next) {

            if (DAFAccount::getAdditionalDonorCount($id) < $maxAdditionalDonors) {
                return redirect(route('daf-additional-donor', $id))->with('success', 'Information has been saved');
            } else {
                return redirect(route('daf-successors', $id))->with('success', 'Information has been saved');
            }
        } else {

            return redirect(route('daf-additional-donor', ['key' => $request->key, 'id' => $id]))->with('success', 'Information has been saved');
        }
    }

    public function deleteAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        // TODO: without any validation?
        Contact::findOrfail($request->contact_id)->delete();
        EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => 'Y' ])->delete();

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_ADDITIONAL_DONOR, $id);

        return redirect(route('daf-additional-donor', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS, $id);
        $user = $dafInfo['user'];
        $successor = $dafInfo['endowment'];

        return view('donor.registration.form-successors', compact('dafInfo', 'user', 'successor', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        if ($request->isSelected == true) {

            $request->validate(['endowment_name' => 'required|string|min:3|max:32']);

            DAFAccount::updateDAFInfo($request, DAFAccount::DAF_SUCCESSORS, $id);

            return $request->save_next ?
                redirect(route('daf-contributions-cash', $id))->with('success', 'Information has been saved') :
                back()->with('success', 'Information has been saved');
        } else {
            DAFAccount::updateDAFInfo($request, DAFAccount::DAF_SUCCESSORS, $id);

            return redirect(route('daf-successors-individuals', $id))->with('success', 'Information has been saved');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessorsIndividuals(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
        $user = $dafInfo['user'];

        $individualsData = $dafInfo['individuals'];
        $individuals = json_decode(json_encode($individualsData, true));

        // get custom fields
        $personFields = Data::getSuccessorsIndividualCustomFields();

        return view('donor.registration.form-successors-individuals', compact('dafInfo', 'user', 'individuals', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function individualFormErrors (Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
        $user = $dafInfo['user'];

        // get custom fields
        $personFields = Data::getSuccessorsIndividualCustomFields();

        return view('donor.registration.form-individual-errors',compact('dafInfo', 'user', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessorsIndividuals(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) $request->merge([
            'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
        ]);

        $model = new DAFSuccessorIndividuals();

        $validator = Validator::make($request->all(), $model->rules());
        if ($validator->fails())
        {
            return redirect()->to(route('daf-individual-form-errors', $id))
                ->withInput($request->input())
                ->withErrors($validator);
        }

        if ($request->isNew == true && $request->contact_id == null) {
            $contactExists = EmailAddress::where(['email_address' => $request->email, 'is_primary' => "N"])->exists();
            if ($contactExists) {
                return redirect()->to(route('daf-individual-form-errors', $id))
                    ->withInput($request->input())
                    ->withErrors(['email.error'=>'This email is already in use.']);
            }

            $registrationType = "successor";
            $contact = DAFAccount::createDAFContact($request, $registrationType);

            $request['contact_id'] = $contact->contact_id;
        }

        if ($request->isNew == false && $request->contact_id != null)
        {
            $contactEmail = EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => "N", 'organization_id' => null])->first();

            if ($contactEmail['email_address'] === $request->email)
            {
                Contact::where('contact_id', $request->contact_id)->update([
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                ]);
            } else {
                $contactExists = EmailAddress::where(['email_address' => $request->email, 'is_primary' => "N", 'organization_id' => null])->exists();
                if ($contactExists) {
                    return redirect()->to(route('daf-individual-form-errors', $id))
                        ->withInput($request->input())
                        ->withErrors(['email.error'=>'This email has already been taken']);
                }

                Contact::where('contact_id', $request->contact_id)->update([
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                ]);
                EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => "N", 'organization_id' => null])->update([
                    "email_address" => $request->email,
                ]);
            }
        }

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);

        return $request->save_next ?
            redirect(route('daf-successors-organizations', $id))->with('success', 'Information has been saved') :
            redirect(route('daf-successors-individuals', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteSuccessorsIndividual(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        // deleting without any validations ???
        Contact::findOrfail($request->contact_id)->delete();
        EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => 'N' ])->delete();

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);

        return redirect(route('daf-successors-individuals', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessorsOrganizations(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);
        $user = $dafInfo['user'];

        $orgInfo = $dafInfo['organizations'];
        $organizations = json_decode(json_encode($orgInfo, true));

        return view('donor.registration.form-successors-organization', compact('dafInfo', 'user', 'organizations', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessorsOrganizations (Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
            'ein' => preg_replace("/[^\d]/", "", $request->ein),
        ]);

        $model = new DAFSuccessorOrganizations();

        $validator = Validator::make($request->all(), $model->rules());
        if ($validator->fails())
        {
            return redirect()->to(route('daf-org-form-errors', $id))
                ->withInput($request->input())
                ->withErrors($validator);
        }

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);

        return $request->save_next ?
            redirect(route('daf-contributions-cash', $id))->with('success', 'Information has been saved') :
            redirect(route('daf-successors-organizations', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function orgFormErrors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);
        $user = $dafInfo['user'];

        return view('donor.registration.form-org-errors',compact('dafInfo', 'user', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteSuccessorOrganization(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);

        return redirect(route('daf-successors-organizations', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributions(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];
        $contributions = $dafInfo['contributions'];
        $securities = [];
        if ($contributions->securities) {
            $securities = $contributions->securities;
            $securities[] =  new DAFSecurity();
        } else {
            $securities[] =  new DAFSecurity();
        }

        return view('donor.registration.form-contributions', compact('dafInfo', 'user', 'contributions', 'securities', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsCash(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        $contributions = $dafInfo['contributions'];

        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        $fundInfo = DAFAccount::getDAFAccount($id);

        if ($contributions->credit_card) {
            $model = $contributions->credit_card;
        } else {
            // main grant model
            $model = new FormDAFPayTo();
            $model->contact_id = $contact->contact_id;
            $model->fund_id = $fundInfo->id;
        }

        return view('donor.registration.form-contributions-cash', compact('dafInfo', 'user', 'contributions', 'model', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveContributionsCash(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        // one or both may be checked or unchecked
        if ($request->check_pay) {
            $request->validate([
                "check_amount" => "required|numeric|min:1",
            ], []);
        }
        if ($request->wire_pay) {
            $request->validate([
                "wire_amount" => "required|numeric|min:1",
                'wire_bank' => 'required|string|min:3|max:32',
            ]);
        }

        if (!$request->check_pay && !$request->wire_pay){
            // user can uncheck both to delete the previously entered amount
            // throw ValidationException::withMessages(['check_pay' => ['Please select payment method.']]);
        }
        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_CASH, $id);

        return $request->save_next ?
            redirect(route('daf-contributions-securities', $id))->with('success', 'Information has been saved') :
            back()->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsSecurities(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        $contributions = $dafInfo['contributions'];
        $securities = [];
        if ($contributions->securities) {
            $securities = $contributions->securities;
            $securities[] =  new DAFSecurity();
        } else {
            $securities[] =  new DAFSecurity();
        }

        return view('donor.registration.form-contributions-securities', compact('dafInfo', 'user', 'contributions', 'securities', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveContributionsSecurities(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $model = new DAFSecurity();

        $validator = Validator::make($request->all(), $model->rules());
        if ($validator->fails())
        {
            return redirect()->to(route('daf-security-form-errors', $id))
                ->withInput($request->input())
                ->withErrors($validator);
        }

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES, $id);

        $types = Data::getContributionTypes();

        if( in_array(Data::DAFR_DONOR_CONTRIBUTIONS_STOCKS, $types) ) {
            $nextRedirectUrl =  'daf-contributions-stocks';
        } else if ( in_array(Data::DAFR_DONOR_CONTRIBUTIONS_OTHERS, $types) ) {
            $nextRedirectUrl = 'daf-contributions-others';
        } else {
            $nextRedirectUrl = 'daf-investments';
        }

        return $request->save_next ?
            redirect(route($nextRedirectUrl, $id))->with('success', 'Information has been saved') :
            redirect(route('daf-contributions-securities', ['tab' => 'securities', 'id' => $id]))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function securityFormErrors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        return view('donor.registration.form-security-errors', compact('dafInfo', 'user', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteContributionSecurity(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES, $id);
        return redirect(route('daf-contributions-securities', ['tab' => 'securities', 'id' => $id]))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsStocks($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        $contributions = $dafInfo['contributions'];

        $stocks = [];
        if ($contributions->stocks) {
            $stocks = $contributions->stocks;
            $stocks[] =  new DAFStocks();
        } else {
            $stocks[] =  new DAFStocks();
        }

        $stocks = json_decode(json_encode($stocks, true));

        return view('donor.registration.form-contributions-stocks', compact('dafInfo', 'user', 'stocks', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveContributionsStocks(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $model = new DAFStocks();
        $request->validate($model->rules(), []);

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_STOCKS, $id);

        return $request->save_next ?
            redirect(route('daf-contributions-others', $id))->with('success', 'Information has been saved') :
            redirect(route('daf-contributions-stocks', $id))->with('success', 'Information has been saved');

        //return redirect(route('daf-contributions-stocks'))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteContributionsStock(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_STOCKS, $id);
        return redirect(route('daf-contributions-stocks', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsOthers($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        $contributions = $dafInfo['contributions'];
        $others = $contributions->others;

        return view('donor.registration.form-contributions-others', compact('dafInfo', 'user', 'others', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveContributionsOthers(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_OTHERS, $id);

        if ($request->save_next) {
            $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);

            if ($dafInfo['status'][DAFAccount::DAF_CONTRIBUTIONS] == DAFAccount::LINK_SAVED) {
                return redirect(route('daf-investments', $id))->with('success', 'Information has been saved');
            } else {
                return redirect()->back()->with('danger', 'No Contributions found');
            }
        } else {
            return redirect(route('daf-contributions-others', $id))->with('success', 'Information has been saved');
        }

//        return $request->save_next ?
//            redirect(route('daf-investments'))->with('success', 'Information has been saved') :
//            redirect(route('daf-contributions-others'))->with('success', 'Information has been saved');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formInvestments($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_INVESTMENTS, $id);
        $user = $dafInfo['user'];

        $model = new FormInvestments();
        $allocations = $dafInfo['allocations'];

        return view('donor.registration.form-investments', compact('dafInfo', 'model', 'user', 'allocations', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveInvestments(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_INVESTMENTS, $id);

        return $request->save_next ?
            redirect(route('daf-authorization', $id))->with('success', 'Information has been saved') :
            redirect(route('daf-investments', $id))->with('success', 'Information has been saved');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formAuthorization($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);
        $user = $dafInfo['user'];
        $authorized = $dafInfo['authorized'] ? 'checked' : '';

        $fullDAFInfo = DAFAccount::getDAFAccount($id);

        return view('donor.registration.form-authorization', compact('dafInfo', 'user', 'authorized', 'fullDAFInfo', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveAuthorization(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        DAFAccount::updateDAFInfo($request, DAFAccount::DAF_AUTHORIZATION, $id);
        Email::dafRegistrationCompleted();

        return redirect(route('daf-application-status', $id));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formReviewApplication($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);
        $user = $dafInfo['user'];
        $fullDAFInfo = DAFAccount::getDAFAccount($id);

        return view('donor.registration.application-status', compact('dafInfo','fullDAFInfo', 'user', 'id'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function downloadApplication($id)
    {
        // assert ownership
        if (!DAFAccount::isOwner($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);
        $user = $dafInfo['user'];
        $fullDAFInfo = DAFAccount::getDAFAccount($id);

        // return view(ClientInfo::clientViewFor('registration.application-pdf', 'donor.'),
        //     compact('dafInfo','fullDAFInfo','user'));

        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView(
            ClientInfo::clientViewFor('registration.application-pdf', 'donor.'),
            compact('dafInfo','fullDAFInfo','user', 'id')
        );
        return $pdf->stream();
    }

    /**
     * TODO:
     * @param $rid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dafPaymentResponse($rid)
    {

        /** @var Transaction $model */
        $model = Transaction::where(['ref_id' => $rid])->first();
        if (!$model) abort('404', 'This transaction does not exist!');

        $id = null; // DAFAccount Id
        if ($model->target_type == 'daf') {
            $id = $model->target_id;
        }

        if ($model->status == Transaction::TDB_STATUS_SUCCESS) {
            $ccResponse = [
                'id' => $model->id,
                'amount' => $model->amount,
                'ref_id' => $model->ref_id,
                'transaction_id' => $model->transaction_id,
                'transaction_date' =>$model->transaction_date,
                'message' => $model->message,
            ];
            DAFAccount::updateContributionCCPaymentInfo($ccResponse, $id);
        }

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        return view('donor.transactions.daf-contributions.response', compact('dafInfo', 'user', 'model', 'id'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function newDafApplication()
    {
        $model = DAFAccount::getNewDAFAccount();
        $id = $model->id;

        // $dafInfo = DAFAccount::getNewDAFInfo($model->id);
        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_DONOR, $id);

        $user = $dafInfo['user'];
        $model = $dafInfo['donor'];

        $personFields = Data::getDonorInfoCustomFields();

        return view('donor.registration.form-account-info', compact('model', 'dafInfo', 'user', 'personFields', 'id'));
    }

}
