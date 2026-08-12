<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends BaseModel
{
    /* @var string */
    protected $table = 'contact_phone';

    /* @var string */
    protected $primaryKey = 'contact_phone_id';

    /* @var boolean */
    public $timestamps = false;

    protected $fillable = [
        "phone_type",
        "phone_number",
    ];

    public function rules()
    {
        return [
            "phone_type" => "required|min:1|max:32",
            "phone_number" => "required|digits:10",
        ];
    }

    /**
     * Get the user that owns the phone.
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    static public function getById($id) {
        return self::find($id);
    }

    public function isPrimary() {
        return PhoneType::isContactPhoneTypePrimary($this->phone_type);
    }

    public function formatPhoneNumber() {
        $numbers_only = preg_replace("/[^\d]/", "", $this->phone_number);
        $this->phone_number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
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
        return $this->contact_phone_id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'contact_phone';
    }

    /*
    * return true | false status fo add / update contact phone
    */
    static public function updateContactPhone($contactId, $organizationId, $phoneNumber, $phoneType)
    {

        $contactPhone = ContactPhone::where([
            'phone_type' => $phoneType,
            'contact_id' => $contactId,
            'organization_id' => null // $organizationId
        ])->first();

        if ($contactPhone) {
            $contactPhone->phone_number = $phoneNumber;
        } else {
            $contactPhone = new ContactPhone();
            $contactPhone->contact_id = $contactId;
            $contactPhone->organization_id = null; //$organizationId;
            $contactPhone->phone_number = $phoneNumber;
            $contactPhone->phone_type = $phoneType;
            $contactPhone->is_cell = 'N';
            $contactPhone->is_fax = 'N';
        }

        $contactPhone->updated_by = Contact::sessionContactId();
        $contactPhone->last_updated =  date('Y-m-d H:i:s');

        return $contactPhone->save();

    }
}