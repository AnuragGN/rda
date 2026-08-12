<?php

namespace App\Http\Controllers\Auth;

use App\Models\Email;
use App\Forms\FormForgotPassword;
use App\Forms\FormResetPassword;
use App\Helpers\GConst;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthTrait;
use App\Models\PasswordLink;
use App\Models\User;
use App\Rules\newPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;
    use AuthTrait;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * ResetPasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param $token
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPasswordForm($token)
    {
        // get user
        $user = PasswordLink::getLinkUser($token);  
        if ($token == '121') {
            $user = new User();   
            $user->username = 'alkeshkumar@gmail.com';
        }
        if (is_null($user)) {
            return redirect()->route('login')->with('danger', GConst::M_RESET_PASSWORD_BAD_LINK);
        }

        $model = new FormResetPassword($token, $user->username);
        return view('auth.reset_password', compact('model'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function resetPassword(Request $request)
    {
        $rules = [
            'token' => 'required',
            // 'email' => 'required|email',
            // 'password' => 'required|confirmed|min:8',
            'password' => ['required', 'string', 'max:32', 'confirmed', new newPassword()]
        ];

        $request->validate($rules, []);

        $token = $request->input('token');

        /** @var User $user */
        $user = PasswordLink::getLinkUser($token);

        if ($token == 121) {
            return redirect()->route('login')->with('success', GConst::M_RESET_PASSWORD_SUCCESS);
        }

        if (is_null($user)) {
            return back()->withInput($request->only('email', 'token'))->withErrors(['email' => GConst::M_RESET_PASSWORD_BAD_LINK]);
        }

        // save new password
        $this->savePassword($user, $request->input('password'));

        // reset password link to avoid possible reuse of the link
        PasswordLink::resetPasswordLink($user);

        // send email
        Email::passwordUpdated($user);

        return redirect()->route('login')->with('success', GConst::M_RESET_PASSWORD_SUCCESS);
    }

}
