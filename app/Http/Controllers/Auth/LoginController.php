<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GConst;
use App\Helpers\GnUtils;
use App\Helpers\SMSManager;
use App\Models\AuthGroup;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Config;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\Auth2FA;
use App\Models\DAFAccount;
use App\Models\Email;
use App\Models\UserActivityLog;
//use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthTrait;
use App\Models\LogActivity;
use App\Models\User;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthTrait;
    //use AuthenticatesUsers {
      //  logout as performLogout;
    // }

    /**
     * originally defined in AuthenticatesUsers
     * redefined here for NTC SSO logout redirect
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
            
    public function logout(Request $request)
    {
        $redirect = $request->session()->get('SSO_LOGOUT_REDIRECT');
        
        // Log activity BEFORE logout
        if ($redirect) {
            UserActivityLog::add(UserActivityLog::ACTION_LOGOUT, UserActivityLog::ST_LOGIN_SSO);
        } else {
            UserActivityLog::add(UserActivityLog::ACTION_LOGOUT, UserActivityLog::ST_LOGIN_DIRECT);
        }

        // ✅ Laravel 12 logout
        Auth::logout();

        // ✅ Destroy session properly
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Redirect handling (your existing logic)

        return redirect('/');
        // return $redirect 
        //     ? redirect($redirect) 
        //     : ($request->wantsJson()
        //         ? new JsonResponse([], 204)
        //         : redirect('/'));
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    const MAX_2FA_ATTEMPTS = 5;
    const MAX_2FA_TOKENS = 3;
    const MAX_ACCOUNT_ACTIVATION_LINKS = 5;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginPage(Request $request)
    {
        return view(ClientInfo::clientViewFor('auth.login'));
    }

    public function onLogin(Request $request)
    {
        return $this->authenticateUser($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws ValidationException
     */
    
    public function authenticateUser(Request $request)
    {
        $activity = LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_LOGIN); 

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('email');
        $password = $request->input('password');
        $activity->data(['username' => $username]);

        /** @var User $userByUsername */
        $userByUsername = User::getFirstByUsername($username);
        if (!$userByUsername) {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }
       
        // get auth user
        $user = User::getFirstByCredentials($username, $password);
       
        if (!$user) {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;

            // update failed attempts & add username in session
            $request->session()->put(GConst::SESSION_USERNAME_ONCE, $userByUsername->username);
           
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        // add to activity logs
        $activity->onModel($user);
        
        if ($user->active !== "Y") {
            $activity->action(LogActivity::ACTION_RESEND_ACCOUNT_ACTIVATION_LINK)->add();

            if (DAFAccount::hasUnapprovedDAFProfile($user)) {
                return $this->activateDAFAccountLink($user);
            } else {
                $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
                throw ValidationException::withMessages([
                    'email' => [trans('auth.failed')],
                ]);
            }
        }

        $auth2FA = ClientConfig::feature('AUTH_2FA');
        if ($auth2FA) {
            return $this->generateAndShow2FAOptions($user, $activity);
        } else {
            return $this->doLogin($request, $user, $activity);
        }
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateDAFAccountLink (User $user)
    {
        return redirect()->route('get-account-activation-link', ['id' => $user->auth_user_id]);
    }

    /**
     * @param User $user
     * @param LogActivity $activity
     * @return Contact
     * @throws ValidationException
     */
    private function getContact(User $user,  $activity=null)
    {
        /** @var LogActivity $activity */
        /** @var Contact $contact */
        $contact = Contact::getByUser($user);
        if (!$contact) {
            // if (env('APP_ENV') == 'dev' && DAFAccount::hasUnapprovedDAFProfile($user)) {
            //    return null;
            // }
            if ($activity) $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages([
                'other' => ['User profile information is not available'],
            ]);
        }
        return $contact;
    }

    /**
     * @param Request $request
     * @param User $user
     * @param LogActivity $activity
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ValidationException
     */
    private function doLogin(Request $request, User $user, LogActivity $activity)
    {
        // check if user is a donor or DAF applicant
        $contact = $this->getContact($user, $activity);
        $daf = DAFAccount::hasUnapprovedDAFProfile($user);

        $role = null;
        $superRole = null;
	
        if ($daf === true) {
            $role = GConst::SESSION_ROLE_DAF;
        } else if (ContactType::isAgency($contact)) {
            $role = GConst::SESSION_ROLE_AGENCY;
        } else if (ContactType::isSupportStaff($contact)) {
            $role = GConst::SESSION_ROLE_SUPPORT_STAFF;
        } else if (ContactType::isDonor($contact)) {
            $role = GConst::SESSION_ROLE_DONOR;
        } else if (env('APP_ENV') == 'dev' && ContactType::isGrantSeeker($contact)) {
            $role = GConst::SESSION_ROLE_GRANTEE;
        } else if (ClientInfo::isCCT() && AuthGroup::isSuperUser($contact)) {
            $role = GConst::SESSION_ROLE_ADMIN;
            $superRole = GConst::SUPER_SESSION_ADMIN;
        } else {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages([
                'other' => ['You do not have a DAF profile'],
            ]);
        }

        // user session
        $request->session()->put(GConst::SESSION_ROLE, $role);
        $request->session()->put(GConst::SESSION_CONTACT_ID, $contact->contact_id);

        // admin session
        if (!empty($superRole)) {
            $request->session()->put(GConst::SUPER_SESSION, GConst::SUPER_SESSION_ADMIN);
            $request->session()->put(GConst::SUPER_SESSION_CONTACT_ID, $contact->contact_id);
        }

        if ($contact->registration_type == 'self_register') {
            $status = $contact->donor_approval_status;
            if ($status == 'Pending' || $status == 'Submitted') {
                // continue
            } else if ($status == 'Rejected') {
                $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
                throw ValidationException::withMessages([
                    'other' => ['Your DAF application has been rejected'],
                ]);
            } else if ($status != 'Approved') {
                $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
                throw ValidationException::withMessages([
                    'other' => ['Your DAF application is not approved.'],
                ]);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();

        return Redirect::intended(GnUtils::userHomeUrl());
        // return redirect(GnUtils::userHomeUrl());

        // return $this->sendFailedLoginResponse($request);
    }

    /******************************************************************************************************************/
    /* 2FA */
    /******************************************************************************************************************/

    /**
     * @param Request $request
     * @return mixed
     */
    public function get2FAResend(Request $request)
    {
        $activity = LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_2FA_RESEND);

        if (!$request->token) {
            return redirect(route('login'))->with('danger', 'The URL is invalid or expired!');
        }
        $model = Auth2FA::where(['token' => $request->token])->first();
        if (!$model) {
            return redirect(route('login'))->with('danger', 'The URL is invalid or expired!');
        }
        $user = User::getById($model->auth_user_id);
        return $this->generateAndShow2FAOptions($user, $activity);
    }

    /**
     * @param User $user
     * @param $activity
     * @return mixed
     */
    private function generateAndShow2FAOptions(User $user, $activity=null)
    {
        /** @var Contact $contact */
        $contact = $this->getContact($user, $activity);
        $phone = $contact->getPrimaryPhoneNumber();
        $phone = GnUtils::phoneNumbersOnly($phone);
        $phone = strlen($phone) < 10 ? null : GnUtils::maskPhoneNumber($phone);

        $email = $user->getAccountEmailAddress(); // important!
        $email = GnUtils::maskEmail($email);

        // delete all previous records
        $models = Auth2FA::where(['auth_user_id' => $user->auth_user_id])->get();
        foreach($models as $model) {
            $model->delete();
        }
        $model = new Auth2FA();
        $model->auth_user_id = $user->auth_user_id;
        $model->code = rand(1000, 9999);
        $model->token = Str::random(32);
        $model->attempts = 0;
        $model->save();
        $token = $model->token;
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();

        return view('auth.2fa.options', compact('email', 'phone', 'token'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get2FAForm(Request $request)
    {
        if (!$request->type || !$request->token) {
            return abort(404);
        }

        $type = $request->type;
        $token = $request->token;
        $model = Auth2FA::where(['token' => $token])->first();

        if (!$model) {
            return redirect(route('login'))->with('danger', 'The URL is invalid or expired!');
        }
        // if ($model->count > self::MAX_2FA_TOKENS )

        /** @var User $user */
        $user = User::getById($model->auth_user_id);

        /** @var Contact $contact */
        $contact = Contact::getByUserId($model->auth_user_id);

        if ($type == 'phone') {
            $phone = $contact->getPrimaryPhoneNumber();
            $address = GnUtils::maskPhoneNumber($phone);
            if ($request->send && $request->send == 1) {
                if (!SMSManager::sendVerificationCode($phone, $model->code)){
                    return redirect(route('2fa-resend', ['token' => $token]))->with('danger', 'The code could not be delivered');
                }
                return redirect(route('2fa-form', ['type' => $type, 'token' => $token]));
            }
        } else if ($type == 'email') {
            if ($request->send && $request->send == 1) {
                Email::auth2FACode($user, $model->code);
                return redirect(route('2fa-form', ['type' => $type, 'token' => $token]));
            }
            $email = $user->getAccountEmailAddress(); // important!
            $address = GnUtils::maskEmail($email);
        } else {
            return abort(404);
        }

        return view('auth.2fa.2fa', compact('type', 'token', 'address'));
    }

    /*** 2FA AUTHENTICATE *********************************************************************************************/
    /**
     * @param Request $request
     * @return mixed
     */
    public function auth2FA(Request $request)
    {
        $activity = LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_2FA);

        // validate and get user-id
        $userId = $this->validate2FAAndGetUserId($request, $activity);

        $user = User::getById($userId);
        if (!$user) {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages([
                'code' => ["User not found. If the problem persists, please contact the site admin!"],
            ]);
        }

        return $this->doLogin($request, $user, $activity);
    }

    /**
     * validate 2FA login
     *
     * @param Request $request
     * @param LogActivity $activity
     * @return mixed
     * @throws ValidationException
     */
    private function validate2FAAndGetUserId(Request $request, LogActivity $activity)
    {
        $request->validate(['token' => 'required|string']);

        // get object
        $createAt = date('Y-m-d H:i:s', strtotime("-15 minutes"));
        $model = Auth2FA::where(['token' => $request->token,])->where('created_at', '>', $createAt)->first();

        if (!$model) {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages([
                'code' => ["The URL is invalid or expired. Please try with a new code."],
            ]);
        }

        $activity->data(['auth_user_id' => $model->auth_user_id, 'attempts' => $model->attempts]);
        if ($model->code == $request->code && $model->attempts < self::MAX_2FA_ATTEMPTS) {
            $userId = $model->auth_user_id;
            $model->delete();
            $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();
            return $userId;
        } else {
            $model->attempts++;
            $model->save();
            $activity->data(['auth_user_id' => $model->auth_user_id, 'attempts' => $model->attempts]);
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;

            if ($model->attempts >= self::MAX_2FA_ATTEMPTS) {
                throw ValidationException::withMessages([
                    'code' => ["You've reached the maximum attempts. Please try with a new code."],
                ]);
            } else {
                throw ValidationException::withMessages([
                    'code' => ['The entered code is incorrect.'],
                ]);
            }
        }
    }

}