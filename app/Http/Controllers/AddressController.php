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
use App\Models\AddressType;
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Helpers\GnUtils;
use App\Models\Organization;
use App\Models\OrganizationAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfileAddress(Request $request)
    {
        GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Address');

        /** @var Contact $profile */
        $type = $request->type;
        $profile = Contact::sessionContact();
        $address = $profile->getAddress($type);
        $isPrimary = AddressType::isContactAddressTypePrimary($type);

        $type = AddressType::getContactAddressTypeByType($address->address_type);
        if (!$type) return abort(404);
        if(!isset($type->label)) $type->label = ucfirst($type->address_type);

        return view('profiles.edit_address', compact('address', 'type', 'isPrimary'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveProfileAddress(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // return $request->all();
        if ($request->id) {
            $model = ContactAddress::getById($request->id);
            if (!$model) abort(404);
        } else {
            $model = new ContactAddress();
        }
        $original = $model->replicate();

        $rules = $model->rules();
        $request->validate($rules);
        $model->fill($request->all());
        $model->contact_id = Contact::sessionContactId();

        $model->last_updated_by = Contact::sessionContactId();
        $model->last_updated =  date('Y-m-d H:i:s');
        $model->save();

        // send notification
        ChangeNotifier::onContactAddressUpdate($original, $model);

        // return redirect()->back()->with('success', 'Address has been saved');
        return redirect()->route('profile')->with('success', 'Address has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteProfileAddress(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        /** @var ContactAddress $model */
        $model = ContactAddress::getById($request->id);
        if (!$model) abort(404);

        if ($model->isPrimary()) {
            return redirect()->back()->with('error', 'You cannot delete your primary address!');
        }

        if ($model->canDelete()) {
            $object = $model->replicate();
            if ($model) $model->delete();
            ChangeNotifier::onContactAddressDelete($object);
            return redirect()->route('profile')->with('success', 'Address has been deleted');
        }
        return redirect()->back()->with('error', 'Forbidden (403): You cannot delete it!');
    }


    /****************************************************************
     * Organization
     ****************************************************************/

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function editOrgAddress(Request $request)
    {
        // GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Organization');

        /** @var Organization $org */
        $org = Organization::find($request->organization_id);
        if (!$org) return abort(404);

        $type = $request->address_type;
        $address = $org->getAddress($type);
        $isPrimary = AddressType::isOrgAddressTypePrimary($type);

        $type = AddressType::getOrgAddressTypeByType($address->address_type);
        if (!$type) return abort(404);
        if(!isset($type->label)) $type->label = ucfirst($type->address_type);

        return view('seeker.org.edit_address', compact('org', 'address', 'type', 'isPrimary'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function saveOrgAddress(Request $request)
    {
        // return $request->all();
        if ($request->organization_address_id) {
            $model = OrganizationAddress::find($request->organization_address_id);
            if (!$model) abort(404);
        } else {
            $model = new OrganizationAddress();
        }

        $rules = $model->rules();
        $request->validate($rules);
        $model->fill($request->all());
        $model->organization_id = $request->organization_id;
        $model->save();

        // return redirect()->back()->with('success', 'Address has been saved');
        return redirect()->route('gs-org-edit-profile', ['id' => $request->organization_id])->with('success', 'Address has been saved');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function deleteOrgAddress(Request $request)
    {
        /** @var OrganizationAddress $model */
        $model = OrganizationAddress::find($request->organization_address_id);
        if (!$model) abort(404);

        if ($model->isPrimary()) {
            return redirect()->back()->with('error', 'You cannot delete the primary address!');
        }

        if ($model->canDelete()) {
            $orgId = $model->organization_id;
            if ($model) $model->delete();
            return redirect()->route('gs-org-edit-profile', ['id' => $orgId])->with('success', 'Address has been deleted');
        }
        return redirect()->back()->with('error', 'Forbidden (403): You cannot delete it!');
    }

}
