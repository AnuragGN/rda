<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use OneLogin\Saml2\Auth;


use App\Helpers\GConst;   
use App\Helpers\GnUtils;
use App\Helpers\SMSManager;
use App\Models\AuthGroup;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\Auth2FA;
use App\Models\DAFAccount;
use App\Models\Email;
use Illuminate\Support\Facades\Auth AS LoginAuth;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthTrait;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
#use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use OneLogin\Saml2\Utils;

class Saml2Controller extends Controller
{
    public function acsLogin(Request $request)
    {
        $base64EncodedData = $request->input('SAMLResponse');
        $saml_response_decoded_xml = base64_decode($base64EncodedData);

        $activity = new LogActivity(LogActivity::NAME_AUTH, 'SSO Login');
        
        Utils::setProxyVars(true);
        $samlConfig = config('saml_config');
        $samlAuth = new Auth($samlConfig);

        // Process the SAML response
        $samlAuth->processResponse();
        
        if (!$samlAuth->isAuthenticated()) {

            $errorMessage = $samlAuth->getLastErrorReason();
            $errorMessage = htmlspecialchars_decode($errorMessage);
            
            $activity->data([
                'saml2Response' => $saml_response_decoded_xml,
                'errorMessage' => $errorMessage]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return view('auth.okta-login-error', [
                'error' =>'User authentication failed',
                'desc' => 'Please retry or contact the administrator.'
            ]);
        }

        $attributes = $samlAuth->getAttributes();
        $gn_sso_id = $attributes['gn_sso_id']['0'];
        
        if (empty($gn_sso_id)) {
           
            $gn_msg = 'GN SSO id not receiving from identity provider';
            $activity->data([
                'saml2Response' => $saml_response_decoded_xml,
                'errorMessage' => $gn_msg]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return view('auth.okta-login-error', [
                'error' =>'Contact id is missing',
                'desc' => 'Please contact the administrator.'
            ]);
        }

        $contact = Contact::where(['gn_sso_id' => $gn_sso_id])->first();

        if (empty($contact)) {
            
            $gn_msg = 'GN SSO id does not exist in database';
            $activity->data([
                'saml2Response' => $saml_response_decoded_xml,
                'errorMessage' => $gn_msg]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return view('auth.okta-login-error', [
                'error' =>'Profile not found',
                'desc' => 'Please contact the administrator.'
            ]);
        }
        
        $auth_user_id = $contact['auth_user_id'];
        $contact_id = $contact['contact_id'];

        if (empty($auth_user_id)) {
            
            $gn_msg = 'auth_user_id does not exist in database';
            $activity->data([
                'saml2Response' => $saml_response_decoded_xml,
                'errorMessage' => $gn_msg]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return view('auth.okta-login-error', [
                'error' =>'Profile not found',
                'desc' => 'Please contact the administrator.'
            ]);
        }

        $user = User::where(['auth_user_id' => $auth_user_id])->first();
        $role = GConst::SESSION_ROLE_AGENCY; 

        $request->session()->put(GConst::SESSION_ROLE, $role);
        $request->session()->put(GConst::SESSION_CONTACT_ID, $contact_id);
        
        $activity->data([
            'saml2Response' => $saml_response_decoded_xml,
            'errorMessage' => '']);
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();
        
        LoginAuth::login($user);
        $request->session()->regenerate();
        return Redirect::intended(GnUtils::userHomeUrl());
    }
}
