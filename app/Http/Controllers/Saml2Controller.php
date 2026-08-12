<?php
namespace App\Http\Controllers;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use OneLogin\Saml2\Auth as SAMLAuth;

use App\Helpers\GConst;
use App\Helpers\GnUtils;
use App\Models\ClientInfo;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\FirmManagement;
use App\Models\UserRegistration;
use App\Models\FaPartner;

use Illuminate\Support\Facades\Redirect;
use OneLogin\Saml2\Utils;

class Saml2Controller extends Controller
{
    public function acsLogin(Request $request)
    {
        $base64EncodedData = $request->input('SAMLResponse');
        $decodedSaml = base64_decode($base64EncodedData);
        $activity = new LogActivity(LogActivity::NAME_AUTH, LogActivity::ST_LOGIN_SSO);
        Utils::setProxyVars(true);

        $samlFileName = $this->getSamlConfigFilename();
        $samlConfig = config($samlFileName);
        $samlAuth = new SAMLAuth($samlConfig);

        $samlAuth->processResponse();

        if (!$samlAuth->isAuthenticated()) {

            $errorMessage = $samlAuth->getLastErrorReason();
            $errorMessage = htmlspecialchars_decode($errorMessage);

            // Maintain Logs
            $activity->data([
                'saml2Response' => $decodedSaml,
                'errorMessage'  => $errorMessage
            ]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            $file = ClientInfo::clientViewFor('auth.okta-login-error');
            return view($file, [
                'error' =>'User authentication failed!',
                'desc' => 'Please retry or contact the administrator.'
            ]);
        }

        $attributes = $samlAuth->getAttributes();
       # $email = $attributes['email'][0] ?? null;
       
       # print_r($email); 
       # print_r($attributes); exit;

        $response = $this->processGNALoginResponse($attributes); 
         
        if ($response['result'] == false) {

            // Maintain Logs
            $activity->data([
                'saml2Response' => $decodedSaml,
                'errorMessage'  => $response['message']['log_description']
            ]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();
           
            // Check firm & Contact
            if (isset($response['error_redirect'])) {

                $sso_id = $response['sso_id'] ?? null;
                $partner_id = $response['partner_id'] ?? null;
                $firm_name = $response['firm_name'] ?? null;
                $first_name = $response['first_name'] ?? null;
                $last_name = $response['last_name'] ?? null;
                $email = $response['email'] ?? null;

                // persist details in session so they survive page refresh
                $request->session()->put('firm_user_not_found', true);
                $request->session()->put('sso_id', $sso_id);
                $request->session()->put('partner_id', $partner_id);
                $request->session()->put('firm_name', $firm_name);
                $request->session()->put('first_name', $first_name);
                $request->session()->put('last_name', $last_name);
                $request->session()->put('email', $email);
                
                if (isset($response['firm_not_found']) && $response['firm_not_found'] === true) {
                   
                    return redirect()->route('advisor-firm-not-found');
                }
                if (isset($response['user_not_found']) && $response['user_not_found'] === true) {
                    
                    return redirect()->route('advisor-firm-user-not-found');
                }
                if (isset($response['status_not_approved']) && $response['status_not_approved'] === true) {
                   
                    return redirect()->route('advisor-account-exist');
                }
                if (isset($response['firms_user_not_found_in_db']) && $response['firms_user_not_found_in_db'] === true) {
                    
                    return redirect()->route('advisor-account');
                }
            }
           
            $file = ClientInfo::clientViewFor('auth.okta-login-error');
            return view($file, ['error' => $response['message']['error'],'desc' => $response['message']['description']]);
        }
      
        $contact_id = $response['contact_id'];
        $auth_user_id = $response['auth_user_id'];

        $user = User::where(['auth_user_id' => $auth_user_id])->first();

        # $role = GConst::SESSION_ROLE_DONOR;
        $role = GConst::SESSION_ROLE_AGENCY;
        $request->session()->put(GConst::SESSION_ROLE, $role);
        $request->session()->put(GConst::SESSION_CONTACT_ID, $contact_id);

        $request->session()->put("SSO_LOGOUT_REDIRECT", $response['logout_redirect_url']);

        $activity->data(['saml2Response' => $decodedSaml,'result' => 'Success']);

        Auth::login($user);
        $request->session()->regenerate();
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();
        UserActivityLog::add(UserActivityLog::ACTION_LOGIN, UserActivityLog::ST_LOGIN_SSO);
        return Redirect::intended(GnUtils::userHomeUrl());
    }

    /**
     * Process GNA login response and validate advisor information.
     *
     * @param array $attributes
     * @return array
     */

    private function processGNALoginResponse(array $attributes) {
        /** ------------------------------------------------------------------
         *  Extract & Validate SSO Attributes
         * ------------------------------------------------------------------ */

        $ssoId  = $attributes['userid'][0] ?? null;
        $partnerId = $attributes['firmid'][0] ?? null;
        $firm = $attributes['firm'][0] ?? null;
        $firstname = $attributes['firstname'][0] ?? null;
        $lastname = $attributes['lastname'][0] ?? null;
        $email = $attributes['email'][0] ?? null;
        
        // Default flags
        $firmNotFound               = false;
        $userNotFound               = false;
        $firmAndUserNotFoundInDb    = false;
        $statusNotApproved          = false;
        $errorRedirect              = true;

        // Initialize default response structure
        $response = [
            'result'                        => false,
            'firm_not_found'                => $firmNotFound,
            'user_not_found'                => $userNotFound,
            'firm_and_user_not_found_in_db' => $firmAndUserNotFoundInDb,
            'status_not_approved'           => $statusNotApproved,
            'error_redirect'                => $errorRedirect,
            'sso_id'                        => $ssoId,
            'partner_id'                    => $partnerId,
            'firm_name'                     => $firm,
            'first_name'                    => $firstname,
            'last_name'                     => $lastname,
            'email'                         => $email,
            'contact_id'                    => null,
            'auth_user_id'                  => null,
            'message'                       => [
                'error'                     => '',
                'description'               => '',
                'log_description'           => ''
            ],
        ];

        /** ------------------------------------------------------------------
         *  Validate Firm / Partner ID
         * ------------------------------------------------------------------ */
        /*if (empty($partnerId)) {
            
            $response['firm_not_found']             = true;
            $response['message']['error']           = 'Partner information missing.';
            $response['message']['description']     = 'We could not verify your partner details. Please contact the administrator.';
            $response['message']['log_description'] = 'Partner ID not received from the identity provider.';

            return $response;
        }

        $record = FaPartner::getPartnerByPartnerId($partnerId);
        if (!$record) {
            
            $response['firm_not_found']             = true;
            $response['message']['error']           = 'Partner information missing.';
            $response['message']['description']     = 'We could not verify your partner details. Please contact the administrator.';
            $response['message']['log_description'] = 'Partner ID do not exist in the system';

            return $response;
        } */

        /** ------------------------------------------------------------------
         *  Validate User ID (Fed ID  / SSO ID)
         * ------------------------------------------------------------------ */
        if (empty($ssoId)) {
            
            $response['user_not_found']             = true;
            $response['message']['error']           = 'User verification failed.';
            $response['message']['description']     = 'Your SSO/Advisor account details could not be validated. Please contact the administrator.';
            $response['message']['log_description'] = 'SSO ID (fed_id) not received from the identity provider.';

            return $response;
        }

        /** ------------------------------------------------------------------
         *  Check if Partner & User exist in DB
         * ------------------------------------------------------------------ */
        $record = UserRegistration::checkUserRegistered($ssoId);
        # echo '<pre>'; print_r($record); exit;
        if (!$record) {
            
            $response['firms_user_not_found_in_db']      = true;
            $response['message']['error']                = 'Account not found in our records.';
            $response['message']['description']          = 'SSO ID do not exist in the system. Redirecting user to advisor account registration page.';
            $response['message']['log_description']      = 'SSO ID do not exist in the system. Redirecting user to advisor account registration page.';
            return $response;
        }
      
        if ($record->status !== 'approved') {

            $response['status_not_approved']        = true;
            $response['message']['error']           = 'Your registration is not approved.';
            $response['message']['description']     = "Your current registration status is: {$record->status}. Please contact the administrator for assistance.";
            $response['message']['log_description'] = "Access denied. Registration status: {$record->status}.";

            return $response;
        }

        /** ------------------------------------------------------------------
         *  Fetch contact by Fed ID
         * ------------------------------------------------------------------ */
        $contact = Contact::getContactByFedId($ssoId);

        if (empty($contact)) {
            $response['message']['error']                = 'You do not have an Advisor account.';
            $response['message']['description']          = 'Please contact the administrator.';
            $response['message']['log_description']      = 'sso_id does not exist in database.';
            return $response; 
        }

        /** ------------------------------------------------------------------
         *  Validate contact record
         * ------------------------------------------------------------------ */
        $contactId   = $contact['contact_id'] ?? null;
        $authUserId  = $contact['auth_user_id'] ?? null;

        if (empty($authUserId)) {
            $response['contact_id']                      = $contactId;
            $response['message']['error']                = 'Advisor account was not found.';
            $response['message']['description']          = 'Please contact the administrator.';
            $response['message']['log_description']      = 'auth_user_id does not exist in database.';
            return $response;
        }

        /** ------------------------------------------------------------------
         *  Successful login response
         * ------------------------------------------------------------------ */
        return [
            'result'                        => true,
            'firm_not_found'                => $firmNotFound,
            'user_not_found'                => $userNotFound,
            'firm_and_user_not_found_in_db' => $firmAndUserNotFoundInDb,
            'sso_id'                        => $ssoId,
            'partner_id'                    => $partnerId,
            'firm_name'                     => $firm,
            'first_name'                    => $firstname,
            'last_name'                     => $lastname,
            'email'                         => $email,
            'contact_id'                    => $contactId,
            'auth_user_id'                  => $authUserId,
            'logout_redirect_url'           => '/m/logout',
            'message'                       => [
                'error'           => null,
                'description'     => null,
                'log_description' => null,
            ],
        ];
    }


    private function getSamlConfigFilename() {

        return ClientInfo::client() . "_saml_config";
    }

    public function advisorFirmNotFound() {

        $file = ClientInfo::clientViewFor('auth.advisor-firm-not-found');
        return view($file);
    }

    public function advisorFirmUserNotFound() {

        $file = ClientInfo::clientViewFor('auth.advisor-firm-user-not-found');
        return view($file);
    }
    
    public function acsLogout() {

        Utils::setProxyVars(true);

        $samlFileName = $this->getSamlConfigFilename();
        $samlConfig = config($samlFileName);
        $samlAuth   = new SAMLAuth($samlConfig);

        // NOTE: it must be called before logout
        UserActivityLog::add(UserActivityLog::ACTION_LOGOUT, UserActivityLog::ST_LOGIN_SSO);

        # Logout the user
        Auth::logout();
        $samlAuth->logout();

        # Redirect to a designated URL after logout
        return redirect('/')->with('status', 'You have been logged out.');
    }
}
