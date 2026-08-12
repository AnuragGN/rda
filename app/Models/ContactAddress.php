<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactAddress extends BaseModel
{
    /* @var string */
    protected $table = 'contact_address';

    /* @var string */
    protected $primaryKey = 'contact_address_id';

    /* @var boolean */
    public $timestamps = false;

    const CA_PRIMARY = "PRIM";
    const CA_MAILING = "MAIL";

    function __construct() {
        parent::__construct();
        $this->country = "USA";
    }

    protected $fillable = [
        "address_type",
        "address_1",
        "address_2",
        "city",
        "state",
        "zip",
        "country"
    ];

    public function rules()
    {
        return [
            "address_type" => "required|min:1|max:32",
            "address_1" => "required|min:1|max:255",
            "address_2" => "sometimes|nullable|min:1|max:255",
            "city" => "required|min:1|max:64",
            "state" => "required|min:1|max:64",
            "zip" => "required|digits:5",
            "country" => "required|min:2|max:64",
        ];
    }

    static public function getById($id) 
    {
        return self::find($id);
    }

    public function getAddressInline() {
        $address = '';
        if ($this->address_1) $address .= $this->address_1;
        if ($this->address_2) $address .=  ', ' . $this->address_2;

        if ($this->city) $address .=  ', ' . $this->city;
        if ($this->county) $address .=  ', ' . $this->county;
        if ($this->state) $address .=  ', ' . $this->state;
        if ($this->country) $address .= ', ' . $this->country;
        if ($this->zip) $address .= ' - ' . $this->zip;

        return $address;
    }

    public function getTwoLineAddress() {
        $address = '';
        if ($this->address_1) $address .= $this->address_1;
        if ($this->address_2) $address .=  ', ' . $this->address_2;
        if ($this->address_1 || $this->address_2) $address .= ',<br>';

        if ($this->city) $address .=  $this->city;
        if ($this->county) $address .=  ', ' . $this->county;
        if (ClientInfo::isJCF() || ClientInfo::isNIF()) {
            if ($this->state) $address .= ', ' . $this->state;
            if ($this->country) $address .= ', ' . $this->country;
            if ($this->zip) $address .= ' - ' . $this->zip;
        } else {
            if ($this->state) $address .= $this->state . ' ';
            if ($this->zip) $address .= ' ' . $this->zip;
        }

        return $address;
    }

    public function getMultiLineAddress() {
        $address = '';
        if ($this->address_1) $address .= $this->address_1 . ',<br/>';
        if ($this->address_2) $address .= $this->address_2 . ',<br/>';
        if ($this->city) $address .= $this->city;
        if ($this->county) $address .= ', ' . $this->county;
        $address .= ',<br />';
        if ($this->state) $address .= $this->state;
        if ($this->country) $address .= ', ' . $this->country;
        if ($this->zip) $address .= ' - ' . $this->zip;

        return $address;
    }

    /**
     * @return bool
     */
    public function isPrimary() {
        return AddressType::isContactAddressTypePrimary($this->address_type);
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        $contactId = Contact::sessionContactId();
        return ($this->contact_id == $contactId);
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->contact_address_id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'contact_address';
    }
}
