<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPhone extends BaseModel
{
    /* @var string */
    protected $table = 'organization_phone';

    /* @var string */
    protected $primaryKey = 'organization_phone_id';

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
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    static public function getById($id) {
        return self::find($id);
    }

    public function isPrimary() {
        return PhoneType::isOrgPhoneTypePrimary($this->phone_type);
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
        // TODO:
        return true;
    }

    /**
     * @return mixed model-id
     */
    public function getModelId()
    {
        return $this->organization_phone_id;
    }

    /**
     * @return string, fund|transaction|etc.
     */
    public function getModelType()
    {
        return 'organization_phone';
    }
}