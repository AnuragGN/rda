<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Columns
 * contact_id
 * _remote_id
 * assistant_id
 * auth_user_id
 * being_reviewed
 * budget_match_amount
 * budget_match_operator
 * company_name
 * created_on
 * deceased
 * dob
 * dod
 * end_match
 * ethnicity
 * fims_id
 * first_name
 * gender
 * informal
 * last_name
 * last_updated
 * last_updated_by
 * middle_name
 * nosync
 * notes
 * other_person_type
 * pan
 * photo_url
 * prefix
 * receive_email
 * religion
 * ssn
 * start_match
 * suffix1
 * suffix2
 * title
 * volunteerism
 * web_site
 * sync_approval
 */
/**
 * Class Contact
 * @package App
 */

class Contact extends BaseModel
{
    const OPTION_YES = 'Y';
    const OPTION_NO = 'N';

    const FIRST_NAME   = 'first-name';
    const NAME = 'name';
    const FULL_NAME  = 'full-name'; // with prefix, suffix

    /* @var string */
    protected $table = 'contact';

    /* @var string */
    protected $primaryKey = 'contact_id';

    /* @var boolean */
    public $timestamps = false;

    protected $fillable = [
        "prefix",
        "first_name",
        "last_name",
        "suffix1",
        "web_site",
        "company_name",
        "dob",
    ];

    public function rules()
    {
        return [
            "first_name" => "required|min:1|max:32",
            "last_name" => "required|min:1|max:32",
            "web_site" => "sometimes|nullable|min:1|max:64",
            "company_name" => "sometimes|nullable|min:1|max:64",
        ];
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->contact_id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'contact';
    }

    // custom code
    public function email()
    {
        return $this->hasOne('App\Models\EmailAddress', "contact_id");
    }

    public function getEmailAddressAttribute()
    {
        return $this->email ? $this->email->email_address : '';
    }

    public function getTwoLineAddress($type="HOME")
    {
        $conditions = [
            'contact_id' => $this->contact_id,
            'address_type' => $type
        ];

        /** @var ContactAddress $address */
        $address = ContactAddress::where($conditions)->first();
        return $address ? $address->getTwoLineAddress() : null;
    }

    /*
     * Multi-line address
     */
    public function getMultiLineAddress($type)
    {
        $conditions = [
            'contact_id' => $this->contact_id,
            'address_type' => $type
        ];

        /** @var ContactAddress $address */
        $address = ContactAddress::where($conditions)->first();
        return $address ? $address->getMultiLineAddress() : null;
    }

    public function canAddPhoneTypes()
    {
        $result = [];
        $phones = $this->phones();
        $types = PhoneType::getContactPhoneTypes();

        foreach($types as $type) {
            $flag = true;
            foreach($phones as $phone) {
                if ($phone->phone_type == $type->phone_type) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                if (!isset($type['label'])) $type['label'] = ucfirst($type->phone_type);
                $result[] = $type;
            }
        }
        return $result;
    }


    public function canAddPhoneTypesXXX()
    {
        $phones = $this->phones();
        $types = PhoneType::getContactPhoneTypes();

        if (Schema::hasColumn('phone_type', 'label')) {
            $phones = array_column($phones->toArray(), 'label');
            $types = array_column($types->toArray(), 'label');
        } else {
            $phones = array_column($phones->toArray(), 'phone_type');
            $types = array_column($types->toArray(), 'phone_type');
        }
        $result = array_diff($types, $phones);
        if (count($result) > 0) $result = array_values($result);
        return $result;
    }

    public function phones()
    {
        return ContactPhone::where([
            'contact_id' => $this->contact_id,
            'organization_id' => null,
            'is_fax' => 'N',
        ])->get();
        // return $this->hasMany('App\Models\ContactPhone', 'contact_id');
    }

    public function getAnyAddress()
    {
        $conditions = [];
        $conditions['contact_id'] = $this->contact_id;

        /** @var ContactAddress $address */
        $conditions['address_type'] = AddressType::getContactAddressTypePrimary();
        $address = ContactAddress::where($conditions)->first();
        if ($address) return $address;

        // get any address
        unset($conditions['address_type']);
        $address = ContactAddress::where($conditions)->first();
        return $address ? $address : new ContactAddress();
    }

    /**
     * @param string $type
     * @return null
     */
    public function getAddress($type=null)
    {
        if (!$type || !AddressType::isValidContactAddressType($type)) {
            $type = AddressType::getContactAddressTypePrimary();
        }
        $conditions = [
            'contact_id' => $this->contact_id,
            'address_type' => $type
        ];

        /** @var ContactAddress $address */
        $address = ContactAddress::where($conditions)->first();
        if (!$address) {
            $address = new ContactAddress();
            $address->address_type = $type;
            $address->contact_id = $this->contact_id;
        }
        return $address;
    }


    static private $theSessionContact = null;

    static public function getByContactId($id)
    {
        if (!$id) return null;
        return self::where(['contact_id' => $id])->first();
    }

    static public function getByRemoteId($id)
    {
        if (!$id) return null;
        return self::where(['_remote_id' => $id])->first();
    }

    static public function getByGlobalId($id)
    {
        if (!$id) return null;
        return self::where(['_global_id' => $id])->first();
    }

    static public function getByUser($user)
    {
        if (!$user) return null;
        return self::getByUserId($user->auth_user_id);
    }

    static public function getByUserId($authUserId)
    {
        return self::where(['auth_user_id' => $authUserId])->first();
    }

    static public function sessionContact()
    {
        if (self::$theSessionContact)
            return self::$theSessionContact;

        /* @var User $user */
        $user = auth()->user();
        if ($user) {
            self::$theSessionContact = self::getByUser($user);
        }
        return self::$theSessionContact;
    }

    static public function sessionContactName($type=Contact::NAME)
    {
        $contact = self::sessionContact();
        if (!$contact) return '';
        switch ($type) {
            case Contact::FIRST_NAME:
                return $contact->first_name;
            case Contact::FULL_NAME:
                return $contact->fullname;
            case Contact::NAME:
            default:
                return $contact->name;
        }
    }

    static public function sessionContactId()
    {
        return \Session::get(\App\Helpers\GConst::SESSION_CONTACT_ID);
        // $contact = self::sessionContact();
        // return $contact->contact_id;
    }

    public function getIsSessionOwnerAttribute()
    {
        $contact = self::sessionContact();
        if ($contact && $contact->contact_id == $this->contact_id)
            return true;
        return false;
    }

    static public function getNameById($contactId)
    {
        $contact = Contact::getByContactId($contactId);
        return $contact ? $contact->name : "";
    }

    public function getNameAttribute()
    {
        if (empty($this->first_name)) return '';
        $name = '' . $this->first_name . ' ' . $this->last_name;
        return $name;
    }

    public function getGrantFromName()
    {
        if (ClientInfo::isCCTorNTC() && !empty($this->informal)) {
            return $this->informal;
        } else {
            return $this->name;
        }
    }

    public function getFullnameAttribute()
    {
        $suffix = '';
        $name = ($this->prefix ? $this->prefix . ' ' : '') . $this->first_name . ' ' . $this->last_name;
        if ($this->suffix1) {
            $suffix = ' (' . ($this->suffix2 ? $this->suffix2 . ', ' : '') . $this->suffix1 . ')';
        }
        return $name . $suffix;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPhone()
    {
        $type = PhoneType::getContactPhoneTypePrimary();
        return ContactPhone::where([
            'phone_type' => $type,
            'contact_id' => $this->contact_id,
            'organization_id' => null,
            'is_fax' => 'N',
        ])->first();
    }

    /**
     * @return string
     */
    public function getPrimaryPhoneNumber()
    {
        $phone = $this->getPrimaryPhone();
        return $phone ? $phone->phone_number : "0";
    }

    public static function updateReceiveEmail ($contactId, $receiveEmail)
    {
        $contact = self::getByContactId($contactId);
        if ($contact) {
            if (!in_array($receiveEmail, [self::OPTION_YES, self::OPTION_NO])) {
                $response = ['status' => 200, 'message' => 'Invalid value selected'];
            } else {
                $contact->receive_email = $receiveEmail;
                $contact->save();
                $response = ['status' => 200, 'message' => 'Receive email setting updated successfully'];
            }
        } else {
            $response = ['status' => 404, 'message' => 'Contact not found'];
        }
        return $response;
    }

    /**
     * @return null|Contact
     */
    static public function getAssistant()
    {
        $contact = self::sessionContact();
        return Contact::getByContactId($contact->assistant_id);
    }

    static public function allowEdit()
    {
        return !ClientInfo::isCCTorNTC();
    }

    public function getContactTypeId()
    {
        $model = ContactTypeContact::where('contact_id', $this->contact_id)->first();
        return $model ? $model->contact_type_id : null;
    }

    static public function getContactByFedId($fed_id)
    {
        return self::where(['gn_sso_id' => $fed_id])->first();
    }
}
