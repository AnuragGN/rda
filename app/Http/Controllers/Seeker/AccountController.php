<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2021
 * Time: 8:34 PM
 */

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\User;
use App\Models\EmailAddress;
use App\Models\AuthGroup;
use App\Models\AuthUserGroupMap;
use App\Models\PhoneType;
use App\Models\ContactPhone;
use Illuminate\Http\Request;
use App\Http\Traits\AuthTrait;

use Illuminate\Support\Facades\Log;

class AccountController  extends Controller
{

    // used to reset password
    use AuthTrait;

    public function getAssistant(Request $request)
    {
        return view('seeker.account.edit-assistant');
    }

    public function saveAssistant(Request $request){}


    public function contactProfile(Request $request)
    {
        $org = Organization::find(1);
        return view('seeker.account.contact-profile', compact('org'));
    }

    public function myProfile(Request $request)
    {
        $org = Organization::find(1);
        return view('seeker.account.my-profile', compact('org'));
    }

    public function info(Request $request)
    {
        $org = Organization::find(1);
        return view('seeker.account.info', compact('org'));
    }

    public function addProfile(Request $request)
    {
        $contact = new Contact();
        return view('seeker.account.add-profile', compact('contact'));
    }

    public function editProfile(Request $request)
    {
        if ($request->id) {
            $contact = Contact::getByContactId($request->id);
        } else {
            $contact = new Contact();
        }
        return view('seeker.account.edit-profile', compact('contact'));
    }

    public function saveProfile(Request $request)
    {
        
        $id = $request->input('contact_id');
        $contact = new Contact();
        $newContact = true;
        if ($id) {
            $contact = Contact::getByContactId($id);
            if ($contact) {
                $newContact = false;
            }
        }

        $email = $request->input('preferred_email');
        $phoneNumber = $request->input('phone');

        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');

        if ($newContact) {
            if (empty($password)) {
                return redirect()->back()->with('error', 'Password is required');  
            } elseif ($password != $password_confirm) {
                return redirect()->back()->with('error', 'Password and confirm password does not match');
            }
            // check if email is alredy used as auth_user
            $user = User::getByUsername($email);
            if ($user) {
                return redirect()->back()->with('error', 'Email address already in use');
            }
        } elseif (!empty($password) && $password != $password_confirm) {
            return redirect()->back()->with('error', 'Password and confirm password does not match');
        }

        $contact->prefix = $request->input('prefix');
        $contact->title = $request->input('title');
        $contact->first_name = $request->input('first_name');
        $contact->middle_name = $request->input('middle_name');
        $contact->last_name = $request->input('last_name');
        $contact->suffix1 = $request->input('suffix1');
        $saveStatus = $contact->save();

        if ($saveStatus) {

            $organizationId = 1; // for now using hardcoded organization_id
            $contactId = $contact->contact_id;

            if ($newContact) {
                // add new contat case
                $authUser = new User();
                $authUser->active = 'Y';
                $authUser->being_reviewed = 'N';
                $authUser->username = $email;
                $authUser->password = $this->encrypt($password);
                $authUser->created_on = date('Y-m-d H:i:s');
                $authUser->modified_on = date('Y-m-d H:i:s');
                $authUser->has_changed_password = "Y";
                $authUser->save();

                $authUserId = $authUser->auth_user_id;
                $contact->auth_user_id = $authUserId;
                $contact->save();

                $emailAddress = new EmailAddress();
                $emailAddress->contact_id = $contactId;
                $emailAddress->email_address = $email;
                $emailAddress->email_address_name = 'Primary';
                $emailAddress->is_primary = 'Y';
                $emailAddress->organization_id = $organizationId;
                $emailAddress->save();

                $orgContact = new OrganizationContact();
                $orgContact->access_level = 1;
                $orgContact->contact_id = $contactId;
                $orgContact->is_default = 'N';
                $orgContact->is_former_resp = 'N';
                $orgContact->organization_id = $organizationId;
                $orgContact->status = 'pending';
                $orgContact->budget_upload = 'N';
                $orgSaveStatus = $orgContact->save();

                $authGroupId = AuthGroup::getGrantSeekerId();
                if ($authGroupId) {
                    AuthUserGroupMap::addAuthUserGroupMap($authGroupId, $authUserId);
                }

            } else {
                // edit case
                $authUser = User::getById($contact->auth_user_id);
                if ($authUser) {
                    $updateUser = false;
                    if ($authUser->username != $email) {
                        $authUser->username = $email;
                        $emailAddress = EmailAddress::getByOrganizationContactId($contactId, $organizationId);
                        if ($emailAddress) {
                            $emailAddress->email_address = $email;
                            $emailAddress->save();
                        }
                        $updateUser = true;
                    }
                    if (!empty($password)) {
                        $authUser->password = $this->encrypt($password);
                        $authUser->has_changed_password = "Y";
                        $updateUser = true;
                    }
                    if ($updateUser) {
                        $authUser->modified_on = date('Y-m-d H:i:s');
                        $authUser->save();
                    }
                }
            }

            // add | update phone number for new / existing staff member
            $phoneType = PhoneType::getContactPhoneTypePrimary();
            Log::info(json_encode(['chk', $phoneType]));
            if ($phoneType) {
                $updatePhone = ContactPhone::updateContactPhone($contactId, $organizationId, $phoneNumber, $phoneType);
            }
        }

        return redirect('/gs/org/staff-management')->with('success', 'Profile has been saved');

        // return redirect()->back()->with('success', 'Profile has been saved');
    }

}