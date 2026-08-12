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

use Illuminate\Support\Facades\Redirect;
use OneLogin\Saml2\Utils;

class Saml25Controller extends Controller
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

        if (ClientInfo::isCGF()) {
            $response = $this->processCGFLoginResponse($attributes);

        } else if (ClientInfo::isNTC()) {
            $response = $this->processNTCLoginResponse($attributes);

        } else if (ClientInfo::isGNA()) {
            $response = $this->processGNALoginResponse($attributes);
        } else {
            # For New Client
        }
        print_r($response); exit;
        if ($response['result'] == false) {

            // Maintain Logs
            $activity->data([
                'saml2Response' => $decodedSaml,
                'errorMessage'  => $response['message']['log_description']
            ]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            // Check firm & Contact
            if (isset($response['error_redirect'])) {

                if (isset($response['firm_not_found']) && $response['firm_not_found'] === true) {
                    $request->session()->flash('display_message', $response['message']['error'] ?? 'Firm information is missing');
                    $request->session()->flash('display_desc', $response['message']['description'] ?? 'Please contact the administrator.');
                    return redirect()->route('firm-not-found');
                }
                return redirect()->route('firm-user-not-found');
            }

            $file = ClientInfo::clientViewFor('auth.okta-login-error');
            return view($file, ['error' => $response['message']['error'],'desc' => $response['message']['description']]);
        }

        $contact_id = $response['contact_id'];
        $auth_user_id = $response['auth_user_id'];

        $user = User::where(['auth_user_id' => $auth_user_id])->first();

        $role = GConst::SESSION_ROLE_DONOR;
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

    private function processCGFLoginResponse($attributes) {

        $firmId = $attributes['firmid'][0] ?? null;
        $firm   = $attributes['firm'][0] ?? null;
        $fed_id = $attributes['userid'][0] ?? null;

        $firm_not_found = false;
        $error_redirect = true;

        // Prepare default response
        $response = [
            'result'         => false,
            'firm_not_found' => $firm_not_found,
            'error_redirect' => $error_redirect,
            'fed_id'         => $fed_id,
            'contact_id'     => null,
            'auth_user_id'   => null,
            'message'        => [
                'error'       => '',
                'description' => '',
                'log_description' => ''
            ]
        ];

        // Validate firm ID
        if (empty($firmId)) {
            $response['firm_not_found'] = true;
            $response['message']['error'] = 'Firm information is missing';
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'firmid not receiving from identity provider.';
            return $response;
        }

        // Check if firm exists
        if (!FirmManagement::isFirmExists($firmId)) {
            $response['firm_not_found'] = true;
            $response['message']['error'] = "The Firm <u><i>$firm</i></u> does not exist in our records.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'Firm ID '.$firmId.' does not exist in GN database.';
            return $response;
        }

        // Check Fed ID
        if (empty($fed_id)) {
            $response['message']['error'] = 'You do not have a DAF account on Charitable Gifting Fund. You can open a new DAF using the link provided below, or contact us for any assistance.';
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id not receiving from identity provider.';
            return $response;
        }

        // Get contact by Fed ID
        $contact = Contact::getContactByFedId($fed_id);
        if (empty($contact)) {
            $response['message']['error'] = "You do not have a DAF account on Charitable Gifting Fund. You can open a new DAF using the link provided below, or contact us for any assistance.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id does not exist in database.';
            return $response;
        }

        // Assign contact details
        $contact_id   = $contact['contact_id'] ?? null;
        $auth_user_id = $contact['auth_user_id'] ?? null;

        if (empty($auth_user_id)) {
            $response['contact_id'] = $contact_id;
            $response['message']['error'] = "No Donor-Advised Fund (DAF) account was found for the firm <u><i>$firm</i></u>. If you would like to open a DAF account, please use the link below or contact GiftingNetwork support for assistance.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'auth_user_id does not exist in database.';
            return $response;
        }

        // Success response
        return [
            'result'         => true,
            'firm_not_found' => $firm_not_found,
            'fed_id'         => $fed_id,
            'contact_id'     => $contact_id,
            'auth_user_id'   => $auth_user_id,
            'logout_redirect_url'   => '/m/firm-user-not-found',
            'message'        => [
                'error'       => null,
                'description' => null,
                'log_description' => null
            ]
        ];
    }

    private function processGNALoginResponse($attributes) {

        $firmId = $attributes['firmid'][0] ?? null;
        $firm   = $attributes['firm'][0] ?? null;
        $fed_id = $attributes['userid'][0] ?? null;

        $firm_not_found = false;
        $error_redirect = true;

        // Prepare default response
        $response = [
            'result'         => false,
            'firm_not_found' => $firm_not_found,
            'error_redirect' => $error_redirect,
            'fed_id'         => $fed_id,
            'contact_id'     => null,
            'auth_user_id'   => null,
            'message'        => [
                'error'       => '',
                'description' => '',
                'log_description' => ''
            ]
        ];

        // Validate firm ID
        if (empty($firmId)) {
            $response['firm_not_found'] = true;
            $response['message']['error'] = 'Firm information is missing';
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'firmid not receiving from identity provider.';
            return $response;
        }

        // Check if firm exists
        if (!FirmManagement::isFirmExists($firmId)) {
            $response['firm_not_found'] = true;
            $response['message']['error'] = "The Firm <u><i>$firm</i></u> does not exist in our records.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'Firm ID '.$firmId.' does not exist in GN database.';
            return $response;
        }

        // Check Fed ID
        if (empty($fed_id)) {
            $response['message']['error'] = 'You do not have a DAF account on Charitable Gifting Fund. You can open a new DAF using the link provided below, or contact us for any assistance.';
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id not receiving from identity provider.';
            return $response;
        }

        // Get contact by Fed ID
        $contact = Contact::getContactByFedId($fed_id);
        if (empty($contact)) {
            $response['message']['error'] = "You do not have a DAF account on Charitable Gifting Fund. You can open a new DAF using the link provided below, or contact us for any assistance.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id does not exist in database.';
            return $response;
        }

        // Assign contact details
        $contact_id   = $contact['contact_id'] ?? null;
        $auth_user_id = $contact['auth_user_id'] ?? null;

        if (empty($auth_user_id)) {
            $response['contact_id'] = $contact_id;
            $response['message']['error'] = "No Donor-Advised Fund (DAF) account was found for the firm <u><i>$firm</i></u>. If you would like to open a DAF account, please use the link below or contact GiftingNetwork support for assistance.";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'auth_user_id does not exist in database.';
            return $response;
        }

        // Success response
        return [
            'result'         => true,
            'firm_not_found' => $firm_not_found,
            'fed_id'         => $fed_id,
            'contact_id'     => $contact_id,
            'auth_user_id'   => $auth_user_id,
            'logout_redirect_url'   => '/m/firm-user-not-found',
            'message'        => [
                'error'       => null,
                'description' => null,
                'log_description' => null
            ]
        ];
    }

    private function processNTCLoginResponse($attributes) {

        $fed_id = $attributes['userid'][0] ?? null;

        $firm_not_found = false;
        $error_redirect = false;

        // Prepare default response
        $response = [
            'result'         => false,
            'firm_not_found' => $firm_not_found,
            'error_redirect' => $error_redirect,
            'contact_id'     => null,
            'auth_user_id'   => null,
            'message'        => [
                'error'       => '',
                'description' => '',
                'log_description' => ''
            ]
        ];

        // Check Fed ID
        if (empty($fed_id)) {
            $response['message']['error'] = 'Required information is missing';
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id not receiving from identity provider.';
            return $response;
        }

        // Get contact by Fed ID
        $contact = Contact::getContactByFedId($fed_id);
        if (empty($contact)) {
            $response['message']['error'] = "Profile not found";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'fed_id does not exist in database.';
            return $response;
        }

        // Assign contact details
        $contact_id   = $contact['contact_id'] ?? null;
        $auth_user_id = $contact['auth_user_id'] ?? null;

        if (empty($auth_user_id)) {
            $response['contact_id'] = $contact_id;
            $response['message']['error'] = "Profile not found";
            $response['message']['description'] = 'Please contact the administrator.';
            $response['message']['log_description'] = 'auth_user_id does not exist in database.';
            return $response;
        }

        // Success response
        return [
            'result'         => true,
            'firm_not_found' => $firm_not_found,
            'fed_id'         => $fed_id,
            'contact_id'     => $contact_id,
            'auth_user_id'   => $auth_user_id,
            'logout_redirect_url'   => 'https://www.northerntrust.com',
            'message'        => [
                'error'       => null,
                'description' => null,
                'log_description' => null
            ]
        ];
    }

    private function getSamlConfigFilename()
    {
        if (ClientInfo::isNTC()) {
            return "saml_config";
        }
        return ClientInfo::client() . "_saml_config";
    }

    public function firmNotFound() {

        $file = ClientInfo::clientViewFor('auth.firm-not-found');
        return view($file, [
            'display_message' => session('display_message'),
            'display_desc'  => session('display_desc')
        ]);
    }

    public function firmUserNotFound() {

        $file = ClientInfo::clientViewFor('auth.firm-user-not-found');
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
