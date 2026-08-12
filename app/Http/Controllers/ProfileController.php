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
use Illuminate\Validation\ValidationException;
use App\Models\AddressType;
use App\Models\Api;
use App\Models\Contact;
use App\Models\ContactGeographicArea;
use App\Models\ContactInterestArea;
use App\Models\ContactPopulationServed;
use App\Helpers\GnUtils;
use App\Models\PhoneType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GiftHistory;

use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return View
     */
    public function view()
    {
        GnUtils::addBreadcrumb('Profile');

        $view = 'view';
        /** @var Contact $profile */
        $profile = Contact::sessionContact();

        $addresses = [];
        $AddressTypes = AddressType::getContactAddressTypes();

        foreach($AddressTypes as $type) {
            $address = [];
            $address['type'] = $type->address_type;
            $address['label'] = isset($type->label) ? $type->label : ucfirst($type->address_type);
            $address['is_primary'] = $type->is_primary;
            $address['address'] = $profile->getMultiLineAddress($type->address_type);
            $addresses[] = $address;
        }

        $primaryPhoneType = PhoneType::getContactPhoneTypePrimary();
        $phones = [];
        foreach($profile->phones() as $phone) {
            $phone->formatPhoneNumber();
            $phone['is_primary'] = ($primaryPhoneType == $phone->phone_type ? true : false);
            $phone['phone_type'] = $phone->phone_type;
            $phone['label'] = PhoneType::getContactPhoneTypeLabel($phone->phone_type);
            $phone['phone_number'] = $phone->phone_number;
            $phones[] = $phone;
        }

        // return $profile;
        // return $profile->phones;
        // return $profile->getAnyAddress()->getTwoLineAddress();
        // return ContactAddress::where(['contact_id'=> $profile->contact_id])->get();

        return view('profiles.view', compact('profile', 'addresses', 'phones', 'view'));
    }



    public function editProfile()
    {
        GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Profile');

        $profile = Contact::sessionContact();
        return view('profiles.edit_profile', compact('profile'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveProfile(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        $profile = Contact::sessionContact();
        $original = $profile->replicate();

        $rules = $profile->rules();
        $request->validate($rules);
        $profile->fill($request->all());

        $profile->last_updated_by = Contact::sessionContactId();
        $profile->last_updated =  date('Y-m-d H:i:s');
        $profile->save();

        // send notification
        ChangeNotifier::onContactProfileUpdate($original, $profile);

        return redirect()->back()->with('success', 'Profile information has been saved');

        // return $request->all();
    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function saveProfileInterests(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        // save interest areas
        $interestAreaIds = $request->input('interest_area_id');
        if (!$interestAreaIds || !count($interestAreaIds)) {
            throw ValidationException::withMessages([
                'interestAreaIds' => 'Please select one or more Interest Areas and Submit.']);
        }
        ContactInterestArea::saveContactInterests($interestAreaIds);

        // save geo areas
        $geographicAreaIds = $request->input('geographic_area_id');
        ContactGeographicArea::saveContactGeographicAreas($geographicAreaIds);

        // save population served
        $populationServedIds = $request->input('population_served_id');
        ContactPopulationServed::saveContactPopulationServed($populationServedIds);

        return redirect()->back()->with('success', 'Interest Profile has been saved');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editProfileInterests()
    {
        GnUtils::addBreadcrumb('Interest Profile', route('profile-interests'));
        GnUtils::addBreadcrumb('Edit');

        $model = new Contact(); // dummy
        return view('profiles.edit_interests', compact('model'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function profileInterests()
    {
        /** @var Contact $profile */
        $profile = Contact::sessionContact();

        return view('profiles.view_interests', compact('profile'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function projectMatches(Request $request)
    {
        $profile = Contact::sessionContact();
        $projects = $this->apiProjectMatches($request);
        return view('profiles.projects', compact('projects'));
    }
    public function apiProjectMatches(Request $request)
    {
        $api = new Api();
        return $api->apiProjectMatches($request);
    }
    public function project($id)
    {
        $profile = Contact::sessionContact();
        return ['project'];
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function organizationMatchesXXX(Request $request)
    {
        $profile = Contact::sessionContact();
        $organizations = $this->apiOrganizationMatches($request);
        // return $organizations;
        return view('profiles.organizations', compact('organizations'));
    }
    public function apiOrganizationMatchesXXX(Request $request)
    {
        $api = new Api();
        return $api->apiOrganizationMatches($request);
    }



    //Profile picture Methods
    public function editProfilePicture()
    {
        GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Change Profile Picture');
        $profile = Contact::sessionContact();
        return view('profiles.edit_profile_picture', compact('profile'));
    }

    public function saveProfilePicture(Request $request)
    {
        // Get the authenticated user's profile
        $profile = Contact::sessionContact();

        // Validate the uploaded profile picture
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('profile_picture')) {
            // Handle the file upload
            $file = $request->file('profile_picture');
            $filename = $profile->contact_id . '_profile.' . $file->getClientOriginalExtension();

            // Define the directory path
            $directory = 'ma/uploads/profile_pictures';

            // Check if the directory exists, and create it if it doesn't
            $path = public_path($directory);
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            // Store the file in the directory
            $file->move($path, $filename);

            // Update the profile with the new picture URL
            $profile->photo_url = '/' . $directory . '/' . $filename;
            $profile->last_updated_by = Contact::sessionContactId();
            $profile->last_updated = date('Y-m-d H:i:s');
            $profile->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    public function deleteProfilePicture(Request $request)
    {
        // Get the authenticated user's profile
        $profile = Contact::sessionContact();
    
        // Check if the profile has a picture
        if ($profile->photo_url) {
            // Define the full file path
            $file_path = public_path($profile->photo_url);
    
            // Remove the photo file if it exists
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
    
            // Update the profile to remove the photo URL
            $profile->photo_url = null;
            $profile->last_updated_by = Contact::sessionContactId();
            $profile->last_updated = date('Y-m-d H:i:s');
            $profile->save();
        }
    
        return redirect()->back()->with('success', 'Profile picture deleted successfully!');
    }
    


}
