<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Data Columns
 * access_level
 * contact_id
 * contact_role
 * department_id
 * is_default
 * is_former_resp
 * last_updated
 * organization_id
 * status
 * _assoc_remote_id
 * budget_upload
 */

/**
 * Class OrganizationContact
 * @package App
 */
class OrganizationContact extends Model
{
    const ACCESS_LEVEL_TYPE_ADMIN = 1;
    const ACCESS_LEVEL_TYPE_STAFF = 2;
    const OPTION_YES = 'Y';
    const OPTION_NO = 'N';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';




    /* @var string */
    protected $table = 'organization_contact';

    /* @var string */
    protected $primaryKey = 'contact_id';

    /* @var boolean */
    public $timestamps = false;

    // Cache
    private $contact = null;

    public static function getAllOrganizationContacts ($organizationId)
    {
    	$organizationContacts = OrganizationContact::where(['organization_id' => $organizationId])->get();
    	return $organizationContacts;
    }

    public static function getOrganizationContact ($organizationId, $contactId)
    {
        $organizationContact = OrganizationContact::where(['organization_id' => $organizationId, 'contact_id' => $contactId])->first();
        return $organizationContact;
    }

    public function getContact()
    {
        if (!$this->contact && $this->contact_id) {
            $this->contact = Contact::getByContactId($this->contact_id);
        }
        return $this->contact;
    }

    /////////////////////////////////////////// GENERIC METHODS ///////////////////////////////////

    public static function setContactAccessLevel ($organizationId, $contactId, $accessLevel)
    {
        $organizationContact = OrganizationContact::getOrganizationContact($organizationId, $contactId);
        if ($organizationContact) {
            if (!in_array($accessLevel, [self::ACCESS_LEVEL_TYPE_ADMIN, self::ACCESS_LEVEL_TYPE_STAFF])) {
                $response = ['status' => 200, 'message' => 'Invalid role selected'];
            } else {
                $organizationContact->access_level = $accessLevel;
                $organizationContact->save();   
                $response = ['status' => 200, 'message' => 'Staff Role updated successfully'];
            }
        } else {
            $response = ['status' => 404, 'message' => 'Contact not found'];
        }
        return $response;
    }

    public static function setContactStatus ($organizationId, $contactId, $status)
    {
        $organizationContact = OrganizationContact::getOrganizationContact($organizationId, $contactId);
        if ($organizationContact) {
            if (!in_array($status, [self::STATUS_APPROVED, self::STATUS_DENIED])) {
                $response = ['status' => 200, 'message' => 'Invalid status selected'];
            } else {
                $organizationContact->status = $status;
                $organizationContact->save();   
                $response = ['status' => 200, 'message' => 'Staff status updated successfully'];
            }
        } else {
            $response = ['status' => 404, 'message' => 'Contact not found'];
        }
        return $response;
    }    

    public static function setDefaultContact ($organizationId, $contactId)
    {
        $organizationContacts = OrganizationContact::getAllOrganizationContacts($organizationId);
        $selectedContact = OrganizationContact::getOrganizationContact($organizationId, $contactId);
        if (count($organizationContacts)) {
            if ($selectedContact) {
                foreach ($organizationContacts as $organizationContact) {
                    if ($organizationContact->contact_id == $contactId) {
                        $organizationContact->is_default = self::OPTION_YES;
                    } else {
                        $organizationContact->is_default = self::OPTION_NO;
                    }
                    $organizationContact->save();
                }
                $response = ['status' => 200, 'message' => 'Default Contact updated successfully'];
            } else {
                // case where selected contact id doesn't belong to the organization
                $response = ['status' => 404, 'message' => 'Contact not found'];
            }
        } else {
            $response = ['status' => 404, 'message' => 'No Contact found'];
        }
        return $response;
    }

}
