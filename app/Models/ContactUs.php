<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    /* @var string */
    protected $table = 'contact_us';

    /* @var string */
    protected $primaryKey = 'contact_us_id';

    /* @var boolean */
    public $timestamps = false;

    /**
     * Get the user that owns the phone.
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    public function saveFromReqMoreInfo($params)
    {
        /** @var Contact $contact */
        $contact = Contact::sessionContact();
        $this->contact_id = $contact->getModelId();

        $this->target_type = $params['target_type'];
        $this->target_value = $params['target_id'];
        $this->contact_name = $params['name'];
        $this->contact_address = null;
        $this->contact_phone = $params['phone'];
        $this->contact_email = $params['email'];
        $this->comment = $params['comment'];
        $this->additional_info = json_encode($params['selected_items']);
        $this->save();

        $this->target_id = $params['target_id'];
        $this->additional_info_array = $params['selected_items'];
        return $this;
    }

    public function getTargetInfo()
    {
        $info = [
            'name' => '',
            'phone' => '',
            'address' => ''
        ];

        if ($this->target_type == 'organization') {
            /** @var Organization $organization */
            $organization = Organization::find($this->target_value);
            if ($organization) {
                $info['name'] = $organization->name . ' (' . $organization->getModelId() . ')';
                $info['address'] = $organization->getAddressFromCity();
            }
        }
        return $info;
    }

}