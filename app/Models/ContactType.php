<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 05-08-2020
 * Time: 19:21
 */

namespace App\Models;

/*
 * contact_type_id
 * contact_type
 */

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactType - Contact Types
 * @package App
 */
class ContactType extends Model
{
    /* @var string */
    protected $table = 'contact_type';

    const ROLE_DONOR = "Donor";
    const ROLE_STAFF = "Staff";
    const ROLE_STUDENT = "Student";
    const ROLE_AGENCY = "Agency Fund Holder";
    const ROLE_CATALOG_ONLY = "Catalog Only";
    const ROLE_BOARD_MEMBER = "Board Member";
    const ROLE_GRANT_SEEKER = "Grant Seeker";

    const ROLE_SUPPORT_STAFF = "Support Staff";

    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'contact_type_id';

    static public function isDonor(Contact $contact)
    {
        $donor = ContactType::where(['contact_type' => ContactType::ROLE_DONOR])->first();
        if (!$donor) return false;
        return ContactTypeContact::where([
            'contact_id' => $contact->contact_id,
            'contact_type_id' => $donor->contact_type_id
        ])->exists();
    }

    static public function isAgency(Contact $contact)
    {
        // TODO:
        $donor = ContactType::where(['contact_type' => ContactType::ROLE_AGENCY])->first();
        if (!$donor) return false;
        return ContactTypeContact::where([
            'contact_id' => $contact->contact_id,
            'contact_type_id' => $donor->contact_type_id
        ])->exists();
    }

    static public function isSupportStaff(Contact $contact)
    {
        // TODO:
        $supportstaff = ContactType::where(['contact_type' => ContactType::ROLE_SUPPORT_STAFF])->first();
        if (!$supportstaff) return false;
        return ContactTypeContact::where([
            'contact_id' => $contact->contact_id,
            'contact_type_id' => $supportstaff->contact_type_id
        ])->exists();
    }

    static public function isGrantSeeker(Contact $contact)
    {
        $donor = ContactType::where(['contact_type' => ContactType::ROLE_GRANT_SEEKER])->first();
        if (!$donor) return false;
        return ContactTypeContact::where([
            'contact_id' => $contact->contact_id,
            'contact_type_id' => $donor->contact_type_id
        ])->exists();
    }

    /**
     * OBSOLETE! DO NOT USE IT!
     * @param Contact $contact
     * @return bool
     */
    static public function isAdmin(Contact $contact)
    {
        // use this call to verify super admin - AuthGroup::isSuperUser($contact)
        return false;
    }

    static public function getContactTypeList()
    {
        $contactTypes = self::pluck('contact_type', 'contact_type_id')->sortBy('contact_type');
        return $contactTypes ? $contactTypes : null;
    }

    static public function getContactTypeId($role)
    {
        $contactTypes = ContactType::where(['contact_type' => $role])->first();
        return $contactTypes ? $contactTypes : null;
    }
}
