<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAddress extends Model
{
    /* @var string */
    protected $table = 'email_address';

    /* @var string */
    protected $primaryKey = 'email_address_id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * Get the user that owns the email.
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    static public function getByOrganizationContactId($contactId, $organizationId) {
        return self::where(['contact_id' => $contactId, 'organization_id' => $organizationId])->first();
    }

    static public function getPrimaryEmailByContactId($contactId) {
        return self::where(['contact_id' => $contactId, 'is_primary' => 'Y'])->first();
    }

    static public function existsByEmailAddress($email) {
        return EmailAddress::where('email_address', 'ilike', $email)->exists();
    }

    static public function getFirstByEmailAddress($email) {
        return EmailAddress::where('email_address', 'ilike', $email)->first();
    }

    static public function existsByPrimaryEmailAddress($email) {
        return EmailAddress::where('email_address', 'ilike', $email)->where(['is_primary' => "Y"])->exists();
    }

    static public function getFirstByPrimaryEmailAddress($email) {
        return EmailAddress::where('email_address', 'ilike', $email)->where(['is_primary' => "Y"])->first();
    }

}