<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2021
 * Time: 8:34 PM
 */

namespace App\Http\Controllers\Seeker;

use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\AddressType;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\PhoneType;
use Illuminate\Http\Request;

class OrganizationController  extends Controller
{

    public function profile(Request $request)
    {
        $org = Organization::find(1);
        $org->image = $org->getImage();
        return view('seeker.org.profile', compact('org'));
    }

    public function editOrgProfile($id)
    {
        // GnUtils::addBreadcrumb('Profile', route('profile'));
        GnUtils::addBreadcrumb('Edit Organization');

        $org = Organization::find($id);
        $profile = Contact::sessionContact();
        return view('seeker.org.edit-profile', compact('org'));
    }

    public function editProfile(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Profile');

        /** @var Organization $org */
        $org = Organization::find(1);
        // return view('seeker.org.edit-profile', compact('org'));

        $addresses = [];
        $AddressTypes = AddressType::getOrgAddressTypes();

        foreach($AddressTypes as $type) {
            $address = [];
            $address['type'] = $type->address_type;
            $address['label'] = isset($type->label) ? $type->label : ucfirst($type->address_type);
            $address['is_primary'] = $type->is_primary;
            $address['address'] = $org->getMultiLineAddress($type->address_type);
            $addresses[] = $address;
        }

        $primaryPhoneType = PhoneType::getOrgPhoneTypePrimary();
        $phones = [];
        foreach($org->phones() as $phone) {
            $phone->formatPhoneNumber();
            $phone['is_primary'] = ($primaryPhoneType == $phone->phone_type ? true : false);
            $phone['phone_type'] = $phone->phone_type;
            $phone['phone_number'] = $phone->phone_number;
            $phones[] = $phone;
        }

        return view('seeker.org.edit-profile-view', compact('org', 'addresses', 'phones'));
    }

    public function saveProfile(Request $request)
    {
        $org = Organization::find(1);
        $org->name = $request->input('name');
        $org->web_site = $request->input('web_site');
        $org->save();
        return redirect()->back()->with('success', 'Profile has been saved');
    }

    public function staffManagement(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Staff');

        $org = Organization::find(1);
        $orgContacts = OrganizationContact::getAllOrganizationContacts(1);
        return view('seeker.org.staff-management', compact('org','orgContacts'));
    }

    public function updateStaffAccessLevel(Request $request)
    {
        $orgId = $request->input('organization_id');
        $contactId = $request->input('contact_id');
        $accessLevelType = $request->input('access_level');
        $response = OrganizationContact::setContactAccessLevel($orgId, $contactId, $accessLevelType);
        return $response; 
    }

    public function updateOrgDefaultContact(Request $request)
    {
        $orgId = $request->input('organization_id');
        $contactId = $request->input('contact_id');
        $response = OrganizationContact::setDefaultContact($orgId, $contactId);
        return $response; 
    }

    public function updateContactReceiveEmail(Request $request)
    {
        $receiveEmail = $request->input('receive_email');
        $contactId = $request->input('contact_id');
        $response = Contact::updateReceiveEmail($contactId, $receiveEmail);
        return $response; 
    }

    public function updateStaffStatus(Request $request)
    {
        $orgId = $request->input('organization_id');
        $contactId = $request->input('contact_id');
        $status = $request->input('status');
        $response = OrganizationContact::setContactStatus($orgId, $contactId, $status);
        return $response; 
    }

    public function organizationStory(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Organization Story');
        $org = Organization::find(1);
        return view('seeker.org.organization-story', compact('org'));
    }

    public function saveOrganizationStory(Request $request)
    {
        $org = Organization::find(1);
        $org->mission = $request->input('mission');
        $org->programs = $request->input('programs');
        $org->history = $request->input('history');
        $org->volunteerism = $request->input('volunteerism');
        $org->save();
        // $inputs = $request->all();
        return redirect()->back()->with('success', 'Organization story has been saved');
    }

    public function interestAreas(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Interest Areas');

        $org = Organization::find(1);
        $orgId = $request->input('org_id');
        if (!$orgId) $orgId = 1;
        $model = new Contact(); // dummy
        return view('seeker.org.interest-areas', compact('org', 'model', 'orgId'));
    }

    public function saveInterestAreas(Request $request)
    {
        return $request->all();

        // save interest areas
        $interestAreaIds = $request->input('interest_area_id');
        // ContactInterestArea::saveContactInterests($interestAreaIds);

        // save geo areas
        $geographicAreaIds = $request->input('geographic_area_id');
        // ContactGeographicArea::saveContactGeographicAreas($geographicAreaIds);

        // save population served
        $populationServedIds = $request->input('population_served_id');
        // ContactPopulationServed::saveContactPopulationServed($populationServedIds);

        return redirect()->back()->with('success', 'Interest Profile has been saved');
    }


    public function budget(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Budget');
        $org = Organization::find(1);
        return view('seeker.org.budget', compact('org'));
    }   

    public function saveBudget(Request $request)
    {
        // $inputs = $request->all();
        return redirect()->back()->with('success', 'Budget has been saved');
    } 


    public function goals(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Goals');
        $org = Organization::find(1);
        return view('seeker.org.goals', compact('org'));
    }

    public function saveGoals(Request $request)
    {
        // $inputs = $request->all();
        return redirect()->back()->with('success', 'Goals has been saved');
    } 

    public function boardMembers(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Board Members');
        $org = Organization::find(1);
        return view('seeker.org.board-members', compact('org'));
    }

    public function saveBoardMembers(Request $request)
    {
        // $inputs = $request->all();
        $org = Organization::find(1);
        $org->board = $request->input('board');
        $org->save();
        return redirect()->back()->with('success', 'Board Members has been saved');
    } 

    public function taxInformation(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Tax Information');
        $org = Organization::find(1);
        return view('seeker.org.tax-information', compact('org'));
    }

    public function saveTaxInformation(Request $request)
    {
        $org = Organization::find(1);
        $org->irs_name = $request->input('irs_name');
        $org->save();
        return redirect()->back()->with('success', 'Tax Information has been saved');
    } 

    public function documentation(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Documentation');
        $org = Organization::find(1);
        return view('seeker.org.documentation', compact('org'));
    }

    public function populationServed(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Population Served');
        $org = Organization::find(1);
        return view('seeker.org.population-served', compact('org'));
    }

    public function savePopulationServed(Request $request)
    {
        // $inputs = $request->all();
        return redirect()->back()->with('success', 'Population Served has been saved');
    } 

    public function certifications(Request $request)
    {
        GnUtils::addBreadcrumb('Edit Certifications');
        $org = Organization::find(1);
        return view('seeker.org.certifications', compact('org'));
    }

    public function saveCertifications(Request $request)
    {
        // $inputs = $request->all();
        return redirect()->back()->with('success', 'Certifications has been saved');
    }

}