<?php

namespace App\Http\Controllers\Auth;

use App\Models\Contact;
use App\Models\Email;
use App\Models\EmailAddress;
use App\Forms\FormForgotPassword;
use App\Helpers\GConst;
use App\Http\Controllers\Controller;
use App\Models\PasswordLink;
use App\Models\User;
use Dotenv\Exception\ValidationException;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    /**
     * ForgotPasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * display form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgotPasswordForm(Request $request)
    {
        $model = new FormForgotPassword();
        $model->email = $request->email;
        return view('auth.forgot_password', compact('model'));
    }

    /**
     * forgot password request
     *
     * @param Request $request
     * @return $this
     */
    public function forgotPassword(Request $request)
    {
        // $this->validateEmail($request);
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // 1. check if email exists in user table
        /** @var User $user */
        $user = User::getByUsername($email);

        if (!$user) {
            // 2. check email address table (email_address), column: email_address
            // TODO: is_primary
            // organization_id MUST be NULL
            $address = EmailAddress::where(['email_address' => $email])->first();
            if ($address) {
                // get contact
                $contact = Contact::getByContactId($address->contact_id);
                if ($contact) {
                    // get user
                    $user = User::getById($contact->auth_user_id);
                    if ($user) {
                        // check if user's username is an email address
                        if (strpos($user->username, '@') !== false) {
                            $message = 'Your account email address is ' . $user->username;
                            return back()->withInput($request->only('email'))->withErrors(['email' => $message]);
                        }
                    }
                }
            }
        }

        // test code
        if ($email == 'alkeshkumar@gmail.com') {
            return back()->with('success', GConst::M_FORGOT_PASSWORD_SUCCESS);
        }

        if (is_null($user)) {
            return back()->withInput($request->only('email'))->withErrors(['email' => GConst::M_USER_EMAIL_NOT_FOUND]);
        }

        // reset password link
        $model = new PasswordLink();
        $link = $model->generatePasswordLink($user);
        
        // send email
        Email::resetPassword($email, $user, $link);

        return back()->with('success', GConst::M_FORGOT_PASSWORD_SUCCESS);

        // return back()->with('status', 'abc1');
        // return back()->withInput($request->only('email'))->withErrors(['email' => 'abc']);
    }

}
