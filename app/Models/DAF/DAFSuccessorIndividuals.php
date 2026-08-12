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

class DAFSuccessorIndividuals {
    public function __construct() {
        $this->isNew = true;
        $this->key = intval(microtime(true) * 1000);
        $this->country = 'US';
        $this->state = State::getDefaultStateCode();
    }

    public $key;
    public $ssn;
    public $zip;
    public $city;
    public $email;
    public $first_name;
    public $isNew;
    public $last_name;
    public $state;
    public $suffix;
    public $country;
    public $preferred_name;
    public $relation;
    public $phone_type;
    public $address_1;
    public $address_2;
    public $share_value;
    public $phone_number;
    public $contact_id;

    public function rules()
    {
        $individualRules = [
            "first_name" => "required|min:1|max:32",
            "last_name" => "required|min:1|max:32",
            "phone_number" => "required|digits:10|gt:0",
            "phone_type" => "required",
            "email" => "required|email:rfc,dns",
            "share_value" => "required|min:1",
            "address_1" => "required|min:3|max:32",
            "address_2" => "nullable|min:3|max:32",
            "city" => "required|min:1|max:32",
            "zip" => "required|digits:5|gt:0",
            "state" => "required",
            "country" => "required",
        ];

        $personFields = Data::getSuccessorsIndividualCustomFields();

        if (in_array(Data::DAFR_DONOR_INFO_SSN, $personFields)) {
            $individualRules += [
                "ssn" => "required|digits:9|gt:0",
            ];
        }
        if (in_array(Data::DAFR_DONOR_INFO_DOB, $personFields)) {
            $individualRules += [
                "dob" => "required|date_format:m-d-Y",
            ];
        }
        if (in_array(Data::DAFR_DONOR_INFO_PREFNAME, $personFields)) {
            $individualRules += [
                "preferred_name" => "nullable|min:3|max:32",
            ];
        }
        if (in_array(Data::DAFR_DONOR_INFO_ADVISOR_RELATIONSHIP, $personFields)) {
            $individualRules += [
                "relationship_key" => "required|max:32",
            ];
        }

        return $individualRules;
    }

    /**
     * @param $params
     * @return mixed
     */
    static public function getIndividualFromParams($params)
    {
        $paramsWithAddress = Data::getAddressWithParams($params);
        return $paramsWithAddress;
    }
}
