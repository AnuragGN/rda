<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers;

use App\Helpers\ChangeNotifier;
use App\Helpers\GConst;
use App\Models\Contact;
use App\Models\ContactPhone;
use App\Helpers\GnUtils;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use App\Models\PhoneType;
use Illuminate\Http\Request;

class PhoneController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfilePhone(Request $request)
    {
        GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Phone');

        if ($request->id) {
            /** @var ContactPhone $phone */
            $phone = ContactPhone::findOrFail($request->id);
            if ($phone) $phone->formatPhoneNumber();
        } else {
            $phone = new ContactPhone();
            $phone->phone_type = $request->type;
        }
        $isPrimary = PhoneType::isContactPhoneTypePrimary($phone->phone_type);

        $type = PhoneType::getContactPhoneTypeByType($phone->phone_type);
        if (!$type) return abort(404);

        return view('profiles.edit_phone', compact('phone', 'type', 'isPrimary'));
    }

    public function saveProfilePhone(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // return $request->all();
        if ($request->id) {
            $model = ContactPhone::findOrFail($request->id);
        } else {
            $model = new ContactPhone();
        }
        $original = $model->replicate();

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        $rules = $model->rules();
        $request->validate($rules);
        $model->fill($request->all());
        $model->formatPhoneNumber();
        $model->contact_id = Contact::sessionContactId();

        $model->last_updated_by = Contact::sessionContactId();
        $model->last_updated =  date('Y-m-d H:i:s');
        $model->save();

        // send notification
        ChangeNotifier::onContactPhoneUpdate($original, $model);

        // return redirect()->back()->with('success', 'Phone has been saved');
        return redirect()->route('profile')->with('success', 'Phone number has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteProfilePhone(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        /** @var ContactPhone $model */
        $model = ContactPhone::getById($request->id);
        if (!$model) abort(404);

        if ($model->isPrimary()) {
            return redirect()->back()->with('error', 'You cannot delete your primary phone number!');
        }
        if ($model->canDelete()) {
            if ($model) {
                $object = $model->replicate();
                $model->delete();
                ChangeNotifier::onContactPhoneDelete($object);
            }
            return redirect()->route('profile')->with('success', 'Phone number has been deleted');
        }
        return redirect()->back()->with('error', 'Forbidden (403): You can not delete it!');
    }


    /****************************************************************
     * Organization
     ****************************************************************/

    public function editOrgPhone(Request $request)
    {
        // GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Organization');

        /** @var Organization $org */
        $org = Organization::find($request->organization_id);
        if (!$org) return abort(404);

        $type = $request->phone_type;
        $phone = $org->getPhone($type);

        $isPrimary = PhoneType::isOrgPhoneTypePrimary($phone->phone_type);
        $type = PhoneType::getOrgPhoneTypeByType($phone->phone_type);
        if (!$type) return abort(404);
        if(!isset($type->label)) $type->label = ucfirst($type->phone_type);

        return view('seeker.org.edit_phone', compact('phone', 'type', 'isPrimary'));
    }

    public function saveOrgPhone(Request $request)
    {
        // return $request->all();
        if ($request->organization_phone_id) {
            $model = OrganizationPhone::findOrFail($request->organization_phone_id);
        } else {
            $model = new OrganizationPhone();
        }

        $request->merge([
            'phone_number' => preg_replace("/[^\d]/", "", $request->phone_number),
        ]);

        $rules = $model->rules();
        $rules["organization_id"] = "required|min:1|max:32";

        $request->validate($rules);
        $model->fill($request->all());
        $model->organization_id = $request->organization_id;
        $model->formatPhoneNumber();
        $model->save();

        // return redirect()->back()->with('success', 'Phone has been saved');
        return redirect()->route('gs-org-edit-profile')->with('success', 'Phone number has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteOrgPhone(Request $request)
    {
        /** @var ContactPhone $model */
        $model = OrganizationPhone::find($request->organization_phone_id);
        if (!$model) abort(404);

        $orgId = $model->organization_id;
        if ($model->isPrimary()) {
            return redirect()->back()->with('error', 'You cannot delete the primary phone number!');
        }
        if ($model->canDelete()) {
            if ($model) $model->delete();
            return redirect()->route('gs-org-edit-profile', ['organization_id' => $orgId])->with('success', 'Phone number has been deleted');
        }
        return redirect()->back()->with('error', 'Forbidden (403): You can not delete it!');
    }

}
