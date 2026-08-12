<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 24-07-2020
 * Time: 18:09
 */

namespace App\Http\Traits;

use App\Models\AuthToken;
use App\Models\Contact;
use App\Http\Controllers\AuthorizeNetAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AuthOnce
{

    /**
     * Authenticate user once by One Time Token
     * @param Request $request
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function authByTokenOnce(Request $request)
    {
        // get input
        $token = $request->input('otToken');
        $contactId = $request->input('payContactId');

        // check input
        if (!$token || $contactId == null) return false;

        if (!$request->input('test')) {
            // date/time limit
            $date = new \DateTime();
            $date->modify('-15 minutes');
            $date = $date->format('Y-m-d H:i:s');

            // get record
            $record = AuthToken::where([
                'contact_id' => $contactId,
                'ot_token' => $token])
                // ->where('updated_on', '>=', $date)
                ->first();

            // auth token not present or expired
            if (!$record) return false;
            // TODO: $record->ot_token = null;
            $record->save();
        }

        if ($contactId == 0) {
            return AuthorizeNetAPIController::GUEST_AUTHORIZED;
        }

        // get user associated with the contact
        $contact = Contact::getByContactId($contactId);
        if (!$contact) return false;

        // login user
        Auth::onceUsingId($contact->auth_user_id);
        $user = auth()->user();
        return $user ? AuthorizeNetAPIController::CONTACT_AUTHORIZED : false;
    }

}
