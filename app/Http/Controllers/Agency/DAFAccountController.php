<?php

namespace App\Http\Controllers\Agency;

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
use App\Models\FaSponser;
use Auth;
use App\Helpers\GnUtils;
use App\Services\DafFlowService;

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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dafAccounts(Request $request)
    {
        GnUtils::addBreadcrumb('DAF Applications');

        $authUserId = auth()->id();
        $sponsors   = FaSponser::getDafSponsors();

        $query = DAFAccount::with('sponsor')
            ->where('auth_user_id', $authUserId)
            ->orderBy('created_at', 'desc');

        /**
         * 🔍 Normal search (?q=)
         */
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where('sponsor_id', $search);
        }

        /**
         * 🔍 Advance search: Full Name
         */
        if ($request->filled('name')) {
            $name  = trim($request->name);
            $parts = preg_split('/\s+/', $name);

            $query->where(function ($q) use ($name, $parts) {

                // Full name: "Deepak M"
                $q->whereRaw("
                    (
                        (donor::jsonb->>'first_name')
                        || ' '
                        || (donor::jsonb->>'last_name')
                    ) ILIKE ?
                ", ["%{$name}%"]);

                // Partial words
                foreach ($parts as $part) {
                    $q->orWhereRaw("(donor::jsonb->>'first_name') ILIKE ?", ["%{$part}%"])
                    ->orWhereRaw("(donor::jsonb->>'last_name') ILIKE ?", ["%{$part}%"]);
                }
            });
        }

        /**
         * 🔎 Filter by DAF ID
         */
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        /**
         * 📅 Date Range Filter
         */
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date   . ' 23:59:59',
            ]);
        }

        /**
         * 🔁 Sync Status
         */
        if ($request->filled('sync_status')) {
            $query->where('sponsor_sync', $request->sync_status);
        }

        $dafAccounts = $query->paginate(10)->withQueryString();

        return view(
            'agency.agency-advisor.daf-accounts',
            [
                'dafAccounts'   => $dafAccounts,
                'sponsors'      => $sponsors,
                'search'        => $request->q,
                'advanceSearch' => $request->all(),
            ]
        );
    }

   
    public function formAccount($sponsorId)
    {
        $sponsor = FaSponser::getDafSponsorById($sponsorId);
        
        if (!$sponsor) {
            abort(404, 'Sponsor not found or inactive.');
        }

        $model = new Contact();

        return view(
            'agency.agency-advisor.daf-registration.form-account',
            compact('model', 'sponsor')
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */

    public function createDafAccount(Request $request)
    {
        // ✅ Step 1: Validate required sponsor input
        // Ensures sponsor_id is present, valid, and exists in sponsors table
        $validated = $request->validate([
            'sponsor_id' => 'required|integer|exists:fa_sponsor,id',
        ]);

        $sponsor_id = $validated['sponsor_id'];

        // ✅ Step 2: Skip auth user creation
        // Using the currently authenticated Advisor's auth_user_id
        // No new user is created for this DAF account

        // ✅ Step 3: Create DAF contact
        // Contact will be linked to the Advisor's existing auth_user_id

        // ✅ Step 4: Create DAF Account for Advisor
        $model = DAFAccount::createDAFAccountByAdvisor($request);

        // ✅ Step 5: Redirect to DAF Account detail page
        return redirect()->route('agency-daf-account-info', [
            'id' => $model->id
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */
    public function saveAccount(Request $request)
    {
        //dd($request->all());
        // input validations
       // $request->validate(['email' => 'email:rfc,dns']);
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
        $userExists = User::existsUsername($email);
        $contactExists = EmailAddress::existsByEmailAddress($email);
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
            return view('agency.agency-advisor.daf-registration.account-activated');
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
        return view('agency.agency-advisor.daf-registration.resend-activation-link', compact('auth_user_id'));
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
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);
        
        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_DONOR, $id);
        if (!$dafInfo) abort(404);

        $user = $dafInfo['user'];
        $model = $dafInfo['donor'];
        $id = $dafInfo['daf_account_id'];
        
        $personFields = Data::getDonorInfoCustomFields();
        return view('agency.agency-advisor.daf-registration.form-account-info', compact('model', 'dafInfo', 'user', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveDonorInfo(Request $request, $id)
    {
        // 🔐 Ownership check
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) {
            abort(403);
        }

        // 📞 Normalize phone
        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) {
            $request->merge([
                'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
            ]);
        }

        // 🔎 Fetch existing donor JSON
        $dafAccount = DAFAccount::findOrFail($id);
        $donorInfo  = json_decode($dafAccount->donor ?? '{}', true);

        /**
         * ✅ EMAIL CHECK (ONLY FIRST TIME)
         */
        if (empty($donorInfo['email']) && filled($request->email)) {

            if (!$this->validateSponsorEmail($request->email)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'email' => 'The email address you entered already exists with the selected sponsor.'
                    ]);
            }
        }

        /**
         * 🔐 LOCK EMAIL AFTER FIRST SAVE
         * - Prevent overwrite
         * - Prevent accidental NULL
         * - Safe against request tampering
         */
        if (!empty($donorInfo['email'])) {
            $request->merge([
                'email' => $donorInfo['email']
            ]);
        }

        // 💾 Save donor info
        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_DONOR, $id);

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_DONOR);
        
        if ($request->has('save_next')) {
            return redirect()
                ->route($nextRoute, $id)
                ->with('success', 'Information has been saved');
        }

        return redirect()
            ->route('agency-daf-account-info', $id)
            ->with('success', 'Information has been saved');
    }


    private function validateSponsorEmail($email)
    {
        // TODO: Implement external email verification logic
        return true; // or false based on future API response
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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
        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_ADDITIONAL_DONOR);
        $nextRedirectUrl = $nextRoute ?: 'agency-daf-successors';
        
        $personFields = Data::getAdditionalDonorInfoCustomFields();
        return view('agency.agency-advisor.daf-registration.form-additional-donor', compact('dafInfo', 'user', 'donorInfo', 'personFields', 'id','nextRedirectUrl'));
    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function saveAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) {
            $request->merge([
                'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
            ]);
        }

        $model = new DAFAdditionalDonor();
        # $request->validate($model->rules(), []);

        $maxAdditionalDonors = ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR');

        if ($request->isNew && (DAFAccount::getAdditionalDonorCount($id) >= $maxAdditionalDonors) ) {
            return redirect()->back()->with('message', 'You can not add more.');
        }

        if ($request->isNew == true && $request->contact_id == null) {

            $contactExists = EmailAddress::existsByPrimaryEmailAddress($request->email);
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
                $contactExists = EmailAddress::existsByPrimaryEmailAddress($request->email);
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

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_ADDITIONAL_DONOR, $id);
        
        if ($request->has('save_next')) {

            if (DAFAccount::getAdditionalDonorCount($id) < $maxAdditionalDonors) {
                return redirect(route('agency-daf-additional-donor', $id))->with('success', 'Information has been saved');
            } else {

                $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_ADDITIONAL_DONOR);
               # $route = ClientConfig::feature('DAF_REG_DAF_TYPE') ? 'daf-type' : $nextRoute;
                return redirect( route($nextRoute, $id))->with('success', 'Information has been saved');
            }
        } else {

            return redirect(route('agency-daf-additional-donor', ['key' => $request->key, 'id' => $id]))->with('success', 'Information has been saved');
        }
    }

    public function deleteAdditionalDonor(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        // TODO: without any validation?
        Contact::findOrfail($request->contact_id)->delete();
        EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => 'Y' ])->delete();

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_ADDITIONAL_DONOR, $id);

        return redirect(route('agency-daf-additional-donor', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS, $id);
        $user = $dafInfo['user'];
        $successor = $dafInfo['endowment'];

        return view('agency.agency-advisor.daf-registration.form-successors', compact('dafInfo', 'user', 'successor', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessors(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        if ($request->isSelected == true) {

            $request->validate(['endowment_name' => 'required|string|min:3|max:32']);

            DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_SUCCESSORS, $id);

            $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_SUCCESSORS);

            return $request->save_next ?
                redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
                back()->with('success', 'Information has been saved');
        } else {
            DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_SUCCESSORS, $id);

            return redirect(route('agency-daf-successors-individuals', $id))->with('success', 'Information has been saved');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessorsIndividuals(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
        $user = $dafInfo['user'];

        $individualsData = $dafInfo['individuals'];
        $individuals = json_decode(json_encode($individualsData, true));

        // get custom fields
        $personFields = Data::getSuccessorsIndividualCustomFields();

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_SUCCESSORS_INDIVIDUALS);
        $nextRedirectUrl = 'agency-daf-successors-organizations';  

        return view('agency.agency-advisor.daf-registration.form-successors-individuals', compact('dafInfo', 'user', 'individuals', 'personFields', 'id', 'nextRedirectUrl'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function individualFormErrors (Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
        $user = $dafInfo['user'];

        // get custom fields
        $personFields = Data::getSuccessorsIndividualCustomFields();

        return view('agency.agency-advisor.daf-registration.form-individual-errors',compact('dafInfo', 'user', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessorsIndividuals(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        if ($request->ssn) $request->merge([
            'ssn' => preg_replace("/[^\d]/", "", $request->ssn),
        ]);

        $model = new DAFSuccessorIndividuals();

        // $validator = Validator::make($request->all(), $model->rules());
        // if ($validator->fails())
        // {
        //     return redirect()->to(route('agency-daf-individual-form-errors', $id))
        //         ->withInput($request->input())
        //         ->withErrors($validator);
        // }

        if ($request->isNew == true && $request->contact_id == null) {
            $contactExists = EmailAddress::where('email_address', 'ilike', $request->email)->where(['is_primary' => "N"])->exists();
            if ($contactExists) {

                return redirect()->to(route('agency-daf-individual-form-errors', $id))
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
                $contactExists = EmailAddress::where('email_address', 'ilike', $request->email)->where(['is_primary' => "N", 'organization_id' => null])->exists();
                if ($contactExists) {
                    return redirect()->to(route('agency-daf-individual-form-errors', $id))
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

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
        
        return $request->has('save_next') ?
            redirect(route('agency-daf-successors-organizations', $id))->with('success', 'Information has been saved') :
            redirect(route('agency-daf-successors-individuals', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteSuccessorsIndividual(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        // deleting without any validations ???
        Contact::findOrfail($request->contact_id)->delete();
        EmailAddress::where(['contact_id' => $request->contact_id, 'is_primary' => 'N' ])->delete();

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);

        return redirect(route('agency-daf-successors-individuals', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formSuccessorsOrganizations(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);
        $user = $dafInfo['user'];

        $orgInfo = $dafInfo['organizations'];
        $organizations = json_decode(json_encode($orgInfo, true));

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_SUCCESSORS);
        $nextRedirectUrl = $nextRoute ?: 'agency-daf-contributions-cash';

        return view('agency.agency-advisor.daf-registration.form-successors-organization', compact('dafInfo', 'user', 'organizations', 'id','nextRedirectUrl'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveSuccessorsOrganizations (Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
            'ein' => preg_replace("/[^\d]/", "", $request->ein),
        ]);

        $model = new DAFSuccessorOrganizations();

        $validator = Validator::make($request->all(), $model->rules());
        if ($validator->fails())
        {
            return redirect()->to(route('agency-daf-org-form-errors', $id))
                ->withInput($request->input())
                ->withErrors($validator);
        }

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_SUCCESSORS);
        return $request->has('save_next') ?
            redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
            redirect(route('agency-daf-successors-organizations', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function orgFormErrors(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);
        $user = $dafInfo['user'];

        return view('agency.agency-advisor.daf-registration.form-org-errors',compact('dafInfo', 'user', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteSuccessorOrganization(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);

        return redirect(route('agency-daf-successors-organizations', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributions(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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

        return view('agency.agency-advisor.daf-registration.form-contributions', compact('dafInfo', 'user', 'contributions', 'securities', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsCash(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_CASH_EQUIVALENTS);
        $nextRedirectUrl = $nextRoute ?: 'agency-daf-contributions-securities';

        return view('agency.agency-advisor.daf-registration.form-contributions-cash', compact('dafInfo', 'user', 'contributions', 'model', 'id', 'nextRedirectUrl'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveContributionsCash(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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
        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_CONTRIBUTIONS_CASH, $id);

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_CASH_EQUIVALENTS);
       # print_r($nextRoute);exit;
        return $request->has('save_next') ?
            redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
            back()->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsSecurities(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES_OR_MUTUAL_FUNDS);
        $nextRedirectUrl = $nextRoute ?: 'agency-daf-contributions-stocks';

        return view('agency.agency-advisor.daf-registration.form-contributions-securities', compact('dafInfo', 'user', 'contributions', 'securities', 'id', 'nextRedirectUrl'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveContributionsSecurities(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $model = new DAFSecurity();

        $validator = Validator::make($request->all(), $model->rules());
        if ($validator->fails())
        {
            return redirect()->to(route('agency-daf-security-form-errors', $id))
                ->withInput($request->input())
                ->withErrors($validator);
        }

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES, $id);

        $types = Data::getContributionTypes();

        if( in_array(Data::DAFR_DONOR_CONTRIBUTIONS_STOCKS, $types) ) {
            $nextRedirectUrl =  'agency-daf-contributions-stocks';
        } else if ( in_array(Data::DAFR_DONOR_CONTRIBUTIONS_OTHERS, $types) ) {
            $nextRedirectUrl = 'agency-daf-contributions-others';
        } else {
            $nextRedirectUrl = 'agency-daf-investments';
        }
        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES_OR_MUTUAL_FUNDS);
        return $request->has('save_next') ?
            redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
            redirect(route('agency-daf-contributions-securities', ['tab' => 'securities', 'id' => $id]))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function securityFormErrors(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        return view('agency.agency-advisor.daf-registration.form-security-errors', compact('dafInfo', 'user', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteContributionSecurity(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_SECURITIES, $id);
        return redirect(route('agency-daf-contributions-securities', ['tab' => 'securities', 'id' => $id]))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsStocks($id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

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

        return view('agency.agency-advisor.daf-registration.form-contributions-stocks', compact('dafInfo', 'user', 'stocks', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveContributionsStocks(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $model = new DAFStocks();
        $request->validate($model->rules(), []);

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_CONTRIBUTIONS_STOCKS, $id);

        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_STOCKS);
        
        return $request->has('save_next') ?
            redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
            redirect(route('agency-daf-contributions-stocks', $id))->with('success', 'Information has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteContributionsStock(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::deleteDAFInfo($request, DAFAccount::DAF_CONTRIBUTIONS_STOCKS, $id);
        return redirect(route('agency-daf-contributions-stocks', $id))->with('success', 'Record deleted successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formContributionsOthers($id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);
        $user = $dafInfo['user'];

        $contributions = $dafInfo['contributions'];
        $others = $contributions->others;

        return view('agency.agency-advisor.daf-registration.form-contributions-others', compact('dafInfo', 'user', 'others', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveContributionsOthers(Request $request, $id)
    {
        // assert ownership
       if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_CONTRIBUTIONS_OTHERS, $id);

        if ($request->has('save_next')) {

            $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_CONTRIBUTIONS, DAFAccount::DAF_CONTRIBUTIONS_OTHERS);
            $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_CONTRIBUTIONS, $id);

            if ($dafInfo['status'][DAFAccount::DAF_CONTRIBUTIONS] == DAFAccount::LINK_SAVED) {
                return redirect(route($nextRoute, $id))->with('success', 'Information has been saved');
            } else {
                return redirect()->back()->with('danger', 'No Contributions found');
            }
        } else {
            return redirect(route('agency-daf-contributions-others', $id))->with('success', 'Information has been saved');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formInvestments($id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_INVESTMENTS, $id);
        $user = $dafInfo['user'];

        $model = new FormInvestments();
        $allocations = $dafInfo['allocations'];

        return view('agency.agency-advisor.daf-registration.form-investments', compact('dafInfo', 'model', 'user', 'allocations', 'id'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveInvestments(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_INVESTMENTS, $id);
        
        $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_INVESTMENTS);

        return $request->has('save_next') ?
            redirect(route($nextRoute, $id))->with('success', 'Information has been saved') :
            redirect(route('agency-daf-investments', $id))->with('success', 'Information has been saved');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formAuthorization($id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);
        $user = $dafInfo['user'];
        $authorized = $dafInfo['authorized'] ? 'checked' : '';

        $fullDAFInfo = DAFAccount::getDAFAccount($id);

        $flow = new DafFlowService();
        $flow->loadConfig($fullDAFInfo->sponsor_id, $id);
        $menu = $flow->buildLeftNavigation();

        return view('agency.agency-advisor.daf-registration.form-authorization', compact('dafInfo', 'user', 'authorized', 'fullDAFInfo', 'id','menu'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveAuthorization(Request $request, $id)
    {
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        DAFAccount::updateDAFInfoByAdvisor($request, DAFAccount::DAF_AUTHORIZATION, $id);
        
        # Email::dafRegistrationCompletedByAdvisor();

        return redirect(route('agency-daf-application-status', $id));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formReviewApplication($id)
    {
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);

       # echo '<pre>';print_r($dafInfo);exit;
        $user = $dafInfo['user'];
        $fullDAFInfo = DAFAccount::getDAFAccount($id);
        
        $flow = new DafFlowService();
        $flow->loadConfig($fullDAFInfo->sponsor_id, $id);
        $menu = $flow->buildLeftNavigation();
       
        return view('agency.agency-advisor.daf-registration.application-status', compact('dafInfo','fullDAFInfo', 'user', 'id','menu'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function downloadApplication($id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_AUTHORIZATION, $id);
        $user = $dafInfo['user'];
        $fullDAFInfo = DAFAccount::getDAFAccount($id);
        
        $flow = new DafFlowService();
        $flow->loadConfig($fullDAFInfo->sponsor_id, $id);
        $menu = $flow->buildLeftNavigation();
        
        // return view(ClientInfo::clientViewFor('registration.application-pdf', 'donor.'),
        //     compact('dafInfo','fullDAFInfo','user'));

        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView(
            ClientInfo::clientViewFor('daf-registration.application-pdf', 'agency.agency-advisor.'),
            compact('dafInfo','fullDAFInfo','user', 'id','menu')
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

        return view('agency.agency-advisor.daf-transactions.daf-contributions.response', compact('dafInfo', 'user', 'model', 'id'));
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

        return view('agency.agency-advisor.daf-registration.form-account-info', compact('model', 'dafInfo', 'user', 'personFields', 'id'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formDAFType(Request $request, $id)
    {
        // assert ownership
        if (!DAFAccount::isAdvisorAuthorizedForDaf($id)) abort(403);

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_TYPE, $id);
        $user = $dafInfo['user'];
       
        $model = DAFAccount::getDAFAccount($id);

        return view('agency.agency-advisor.daf-registration.daf-type.index', compact('id', 'dafInfo', 'user', 'model'));

    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function storeDAFType(Request $request, $id)
    {
        $model = DAFAccount::getDAFAccount($id);
        if (!$model) return redirect()->back();

        $model->daf_type = $request->daf_type;
        $model->save();

        if ($request->has('save_next')) {

            $nextRoute = DafFlowService::getNextRouteByOrder($id, DAFAccount::DAF_TYPE);

            return redirect( route($nextRoute, $id))->with('success', 'Information has been saved');
        }

        return redirect( route('agency-daf-type', ['id' => $id]))->with('success', 'Information has been saved');
    }
}
