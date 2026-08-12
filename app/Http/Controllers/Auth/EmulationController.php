<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/8/2023
 * Time: 3:38 PM
 */

namespace App\Http\Controllers\Auth;

use App\Helpers\GConst;
use App\Helpers\GnUtils;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmulationController extends Controller
{

    public function onStartEmulation(Request $request)
    {
        // $adminContact = Contact::sessionContact();

        if (!empty($request->gn_id)) {
            $contact = Contact::getByContactId($request->gn_id);
        } else if(!empty($request->remote_id)) {
            $contact = Contact::getByRemoteId($request->remote_id);
        } else if(!empty($request->global_id)) {
            $contact = Contact::getByGlobalId($request->global_id);
        } else {
            $contact = null;
        }

        if (empty($contact)) return redirect(route("emulation-404"));
        $userId = $contact->auth_user_id;
        $user = User::getById($userId);
        if (empty($user)) return redirect(route("emulation-404"));

        Auth::login($user);
        $request->session()->regenerate();

        // TODO: not required for admin session
        // $request->session()->put(GConst::SUPER_SESSION, GConst::SUPER_SESSION_ADMIN);
        // $request->session()->put(GConst::SUPER_SESSION_CONTACT_ID, $adminContact->contact_id);

        // set donor session
        $request->session()->put(GConst::SESSION_ROLE, GConst::SESSION_ROLE_DONOR);
        $request->session()->put(GConst::SESSION_CONTACT_ID, $contact->contact_id);

        return redirect(GnUtils::userHomeUrl());
        //return $this->startEmulation($user);
        // return "s e id=" . $id;
    }

    public function startEmulation($user)
    {
        return "s e";
        // 1. make sure logged in user is admin

        // 2. login as Donor and start session as a donor
        //    also start super-session for admin

    }
}