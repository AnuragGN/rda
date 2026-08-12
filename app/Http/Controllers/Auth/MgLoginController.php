<?php

namespace App\Http\Controllers\Auth;

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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthTrait;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class MgLoginController extends Controller
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

    public function mgLogin(Request $request) {

        $activity = new LogActivity(LogActivity::NAME_AUTH, 'MGP Login');

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 422);
        }

        $hostname = $request->input('hostname');
        $username = $request->input('user_id');
        $password = $this->encrypt($request->input('password'));

        $user = User::where(['username' => $username])->first();

        if (!$user) {

            $activity->data([
                'username' => $username,
                'error' => 'FA does not exist'
            ]);

            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return response()->json([
                'auth_success' => false,
                'hostname' => $hostname,
                'message' => 'FA does not exist.',
                'gn_sso_id' => 'NA',
                'url' => 'NA',
            ], 401);
        }

        $user = User::where(['username' => $username, 'password' => $password])->first();

        if (!$user) {
            
            $activity->data([
                'username' => $username,
                'error' => 'Invalid password.'
            ]);

            $activity->description(LogActivity::DESCRIPTION_FAILED)->add();

            return response()->json([
                'auth_success' => false,
                'hostname' => $hostname,
                'message' => 'Invalid password.',
                'gn_sso_id' => 'NA',
                'url' => 'NA',
            ], 401);
        }

        $contact = Contact::where(['auth_user_id' => $user->auth_user_id])->first();

        if(!$contact->gn_sso_id) {

            $contact->gn_sso_id = $this->generateGnSsoId();

            $contactUpdate = Contact::findOrFail($contact->contact_id);
            $contactUpdate->gn_sso_id = $contact->gn_sso_id;
            $contactUpdate->save();
        }

        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();
        return response()->json([
            'auth_success' => true,
            'hostname' => $hostname,
            'message' => 'Record is found',
            'gn_sso_id' => $contact->gn_sso_id,
            'url' => 'https://uat-ffp.giftingnetwork.org/mgp-api/authentication'
        ], 200);
    }

    public function generateGnSsoId() {

        $uniqueId = uniqid();
        $randomChars = Str::random(3);
        $uniqueString = $uniqueId . $randomChars;
        
        while (Contact::where('gn_sso_id', $uniqueString)->exists()) {

            $uniqueId = uniqid();
            $randomChars = Str::random(3);
            $uniqueString = $uniqueId . $randomChars;
        }
        return $uniqueString;
    }
}