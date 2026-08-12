<?php

namespace App\Http\Controllers\Agency;

use App\Helpers\Data;
use App\Http\Controllers\Controller;
use App\Models\ClientInfo;
use App\Models\Config;
use App\Models\Contact;
use App\Models\ClientConfig;
use App\Models\User;
use App\Models\UserRegistration;
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

class UserRegistrationController extends Controller
{
    // for password encryption
    use AuthTrait;

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    
    public function advisorFormAccount(Request $request) {
        
        $sso_id      = $request->session()->get('sso_id');
        $partner_id  = $request->session()->get('partner_id');
        $firm_name   = $request->session()->get('firm_name');

        $record = UserRegistration::getSSOUserRegistrationRecord($partner_id, $sso_id);
        if ($record) {
            return redirect(route('advisor-account-exist'));
        }
        return view('agency.agency-advisor.advisor-registration.form-account', compact('sso_id', 'partner_id', 'firm_name'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */
    public function advisorSaveAccount(Request $request)
    {
        $email = $request->email;
        $rules = [
            "first_name" => "required|min:1|max:32",
            "last_name" => "required|min:1|max:32",
            'email' => 'required|email'
        ];
        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.'
        ];
        
        $request->validate($rules, $messages);
        
        // require SSO identifiers (user_id and firm_id) from the form/session
        $missing = [];
        if (empty($request->sso_id)) {
            $missing['sso_id'] = 'Invalid Request, Please initiate registration via SSO.';
        }
       
        if (!empty($missing)) {
            throw ValidationException::withMessages($missing);
        }

        // check if email already exists in sso enquiries
        $advisorExists = UserRegistration::where('email', $request->email)->exists();

        if ($advisorExists) {
            throw ValidationException::withMessages(['email' => ['This email is already in use.']]);
        }
        
        // Create Advisor Enquiry
        $advisor = new UserRegistration();
        $advisor->first_name = $request->first_name; 
        $advisor->last_name = $request->last_name;
        $advisor->firm_name = $request->firm_name;
        $advisor->email = $request->email;
        $advisor->phone = $request->phone;
        $advisor->comment = $request->comment;
		$advisor->acknowledgment = $request->accept_advisor;
        $advisor->sso_id = $request->sso_id;
        $advisor->affiliate_id = $request->partner_id;
        $advisor->status = 'new';
        $advisor->save();

        if ($advisor) {

            $advisorRegistrationId = $advisor->id;
            $ticket = UserRegistration::createAdvisorTicket($request, $advisorRegistrationId);
       
            Email::advisorRegistration($advisor);
        }
        return redirect()->route('advisor-account-created');
    }

    public function advisorAccountCreated()
    {
        return view('agency.agency-advisor.advisor-registration.account-created');
    }

    public function advisorAccountExist()
    {
        return view('agency.agency-advisor.advisor-registration.account-exist');
    }
}
