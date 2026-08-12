<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GnUtils;
use App\Models\Email;
use App\Forms\FormChangePassword;
use App\Helpers\GConst;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthTrait;
use App\Models\LogActivity;
use App\Rules\newPassword;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\DAFAccount;

class ChangePasswordController extends Controller
{

    // use ResetsPasswords;
    use AuthTrait;

    /**
     * @param $token
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePasswordForm()
    {
        GnUtils::addBreadcrumb('Change Password');

        $view = 'edit';
        $model = new FormChangePassword();
        return view('auth.passwords.change', compact('model', 'view'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function changePassword(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // NTC SSO-users can't use this feature
        if (GnUtils::isNTCSSOUser()) {
            return back()->with('danger', "You do not have a password to change.");
        }

        $activity = LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_CHANGE_PASSWORD);
        $activity->add();

        $rules = [
            'current_password' => 'required',
            'password' => ['required', 'string', 'max:32', 'confirmed', new newPassword()]
        ];

        $request->validate($rules, []);

        /** @var User $user */
        $user = User::getSessionUser();
        if ($user) $activity->onModel($user);

        $user = User::getFirstByCredentials($user->username, $request->input('current_password'));
        if (!$user) {
            $activity->description(LogActivity::DESCRIPTION_PASSWORD_MISMATCH)->add();;
            throw ValidationException::withMessages([
                'email' => ['Current password does not match'],   
            ]);
        } 
       # print_r($user);die;
        // save new password
        $this->savePassword($user, $request->input('password'));
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();

        // send email
        Email::passwordUpdated($user);

        return redirect()->route('profile')->with('success', GConst::M_CHANGE_PASSWORD_SUCCESS);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changeDAFUserPasswordForm ($id)
    {
        // GnUtils::addBreadcrumb('Change Password');

        $dafInfo = DAFAccount::getDAFInfo(DAFAccount::DAF_SUCCESSORS, $id);
        $user = $dafInfo['user'];
        $view = 'edit';
        $model = new FormChangePassword();
        return view('donor.registration.change-password', compact('model', 'view', 'dafInfo', 'user', 'id'));
    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function changeDAFUserPassword(Request $request)
    {
       // dd($request->all());
        $activity = LogActivity::safe(LogActivity::NAME_AUTH, LogActivity::ACTION_CHANGE_PASSWORD);
        $activity->add();

        $rules = [
            'current_password' => 'required',
            'password' => ['required', 'string', 'max:32', 'confirmed', new newPassword()]
        ];

        $request->validate($rules, []);

        /** @var User $user */
        $user = User::getSessionUser();
        if ($user) $activity->onModel($user);

        $user = User::getFirstByCredentials($user->username, $request->input('current_password'));
        if (!$user) {
            $activity->description(LogActivity::DESCRIPTION_PASSWORD_MISMATCH)->add();;
            throw ValidationException::withMessages([
                'email' => ['Current password does not match'],
            ]);
        }

        // save new password
        $this->savePassword($user, $request->input('password'));
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();

        // send email
        //Email::passwordUpdated($user);

        return redirect()->back()->with('success', GConst::M_CHANGE_PASSWORD_SUCCESS);
    }
}
