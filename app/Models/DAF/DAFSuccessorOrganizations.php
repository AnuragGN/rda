<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/14/2022
 * Time: 2:15 PM
 */

namespace App\Models\DAF;

use App\Helpers\Data;
use App\Models\ClientInfo;
use App\Models\State;

class DAFSuccessorOrganizations {
    public function __construct() {
        $this->isNew = true;
        $this->key = intval(microtime(true) * 1000);
        $this->country = 'US';
        $this->state = State::getDefaultStateCode();
    }

    public $ein;
    public $key;
    public $zip;
    public $city;
    public $isNew;
    public $state;
    public $giving;
    public $country;
    public $org_name;
    public $address_1;
    public $address_2;
    public $phone_number;

    public function rules()
    {
        $orgRules = [
            "giving" => "required|min:1",
            "org_name" => "required|min:3|max:64",
            "phone_number" => "required|digits:10|gt:0",
            "ein" => "required|digits:9|gt:0",
            "address_1" => "required|min:3|max:32",
            "address_2" => "nullable|min:3|max:32",
            "city" => "required|min:1|max:32",
            "zip" => "required|digits:5|gt:0",
            "state" => "required",
            "country" => "required",
        ];

        if (ClientInfo::isHGA()) {
            $orgRules += [
                "contact_name" => "required|min:3|max:32",
            ];
        } else {
            $orgRules += [
                "contact_name" => "sometimes|min:3|max:32",
            ];
        }

        return $orgRules;
    }

    static public function getOrganizationFromParams($params)
    {
        $paramsWithAddress = Data::getAddressWithParams($params);
        return $paramsWithAddress;
    }

}
