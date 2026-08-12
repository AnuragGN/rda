<?php

namespace App\Http\Controllers\Auth;

use App\Forms\FormChangeEmail;
use App\Forms\FormConfirmEmail;
use App\Models\EmailAddress;
use App\Models\EmailChangeRequest;
use Illuminate\Support\Str;
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

class ChangeEmailController extends Controller
{

    // use ResetsPasswords;
    use AuthTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changeEmailForm(Request $request)
    {
        GnUtils::addBreadcrumb('Change Email');

        $model = new FormChangeEmail();
        return view('auth.email.change', compact('model'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changeEmail(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        $request->validate(['email' => 'email:rfc,dns']);
        $email = $request->email;

        GnUtils::addBreadcrumb('Change Email');

        $token = Str::random(40);
        $url = url(route('confirm-change-email-form', [
            'email' => $email,
            'token' => $token
        ]));

        // make sure the email is not already in use
        $userExists = User::where(['username' => $email])->exists();
        $contactExists = EmailAddress::where(['email_address' => $email])->exists();
        if ($userExists || $contactExists) {
            throw ValidationException::withMessages(['email' => ['This email is already in use.']]);
        }

        // save in database
        $model = new EmailChangeRequest();
        $model->email = $email;
        $model->token = $token;
        $model->auth_user_id = User::getSessionUserId();
        $model->status = EmailChangeRequest::ECR_RECEIVED;
        $model->save();

        // send email to the user
        Email::emailChangeRequest($email, $url);

        return view('auth.email.change-submitted', compact('email'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function validateRequestAndGetRecord(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'email:rfc,dns',
        ]);

        // get object
        $createAt = date('Y-m-d H:i:s', strtotime("-15 minutes"));
        return EmailChangeRequest::where([
            'token' => $request->token,
            'email' => $request->email,
            'status' => EmailChangeRequest::ECR_RECEIVED
        ])->where('created_at', '>', $createAt)->first();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function confirmChangeEmailForm(Request $request)
    {
        try {
            // get ECR record
            $ecr = $this->validateRequestAndGetRecord($request);
        } catch (\Exception $e) {
            return view('auth.email.invalid-expired');
        }

        if (!$ecr) {
            return view('auth.email.invalid-expired');
        }

        $token = $request->token;
        $email = $request->email;

        GnUtils::addBreadcrumb('Confirm Email Change');

        $model = new FormConfirmEmail();
        return view('auth.email.confirm', compact('model', 'token', 'email'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws ValidationException
     */
    public function confirmChangeEmail(Request $request)
    {
        $activity = new LogActivity(LogActivity::NAME_EMAIL, LogActivity::ACTION_CHANGE_EMAIL);

        $request->validate(['password' => 'required|string']);

        // get ECR record
        $ecr = $this->validateRequestAndGetRecord($request);
        if (!$ecr) {
            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();;
            throw ValidationException::withMessages(['token' => ['The entered URL is invalid or expired']]);
        }

        // get user
        $password = $this->encrypt($request->password);
        /** @var User $user */
        $user = User::where(['auth_user_id' => $ecr->auth_user_id, 'password' => $password])->first();
        if (!$user) {
            $activity->description(LogActivity::DESCRIPTION_PASSWORD_MISMATCH)->add();;
            throw ValidationException::withMessages([
                'email' => ['Current password does not match'],
            ]);
        }

        // change email of contact, and username
        $emailAddress = EmailAddress::where(['contact_id' => $user->getContactId()])->first();
        if (!$emailAddress){
            $activity->description(LogActivity::DES_UNEXPECTED_ERROR)->add();;
            throw ValidationException::withMessages([
                'email' => ['Email not found! Please contact site-admin.'],
            ]);
        }
        // update contact email address
        $oldEmail = $emailAddress->email_address;
        $emailAddress->email_address = $request->email;
        $emailAddress->save();

        // update contact username
        $user->username = $request->email;
        $user->save();

        // update email change request
        $ecr->status = EmailChangeRequest::ECR_COMPLETED;
        $ecr->save();

        // send email to the user
        $name = $user->getContactName();
        $to = array(['email' => $oldEmail], ['email' => $request->email]);
        Email::emailChangeConfirmation($to, $name, $request->email);

        // log activity
        $activity->onModel($user)->description(LogActivity::DESCRIPTION_SUCCESS)->add();

        return view('auth.email.confirmation', ['email' => $request->email]);
    }

}
