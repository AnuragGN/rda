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

class DAFAdditionalDonor {
    public function __construct() {
        $this->isNew = true;
        $this->key = intval(microtime(true) * 1000);
        $this->country = 'US';
        $this->state = State::getDefaultStateCode();
        $this->mailing_state = State::getDefaultStateCode();
        $this->same_address = true;

        // $time = strtotime("-40 year", time());
        // $this->dob = date("m-d-Y", $time);
        $this->dob = '';
    }

    public $key;
    public $preferred_name;
    public $contact_id;
    public $prefix;
    public $first_name;
    public $last_name;
    public $suffix;
    public $dob;
    public $phone_number;
    public $phone_type;
    public $email;

    public $address_1;
    public $address_2;
    public $city;
    public $state;
    public $zip;
    public $country;

    public $same_address;
    public $mailing_address_1;
    public $mailing_address_2;
    public $mailing_city;
    public $mailing_state;
    public $mailing_zip;
    public $mailing_country;

    public function rules()
    {
        $additionalDonorRules = [
            "first_name" => "required|min:1|max:32",
            "last_name" => "required|min:1|max:32",
            "phone_number" => "required|digits:10|gt:0",
            "phone_type" => "required",
            "email" => "required|email:rfc,dns",
            "address_1" => "required|min:3|max:32",
            "address_2" => "nullable|min:3|max:32",
            "city" => "required|min:1|max:32",
            "zip" => "required|digits:5|gt:0",
            "state" => "required",
            "country" => "required",
        ];

        $personFields = Data::getAdditionalDonorInfoCustomFields();

        if (isset($_REQUEST[Data::DAFR_DONOR_INFO_FUND_PRIVILEGES]) && $_REQUEST[Data::DAFR_DONOR_INFO_FUND_PRIVILEGES] == Data::DAFR_DONOR_INFO_FUND_PRIVILEGE_FULL ) {

            if (in_array(Data::DAFR_DONOR_INFO_SSN, $personFields)) {
                $additionalDonorRules += [
                    "ssn" => "required|digits:9|gt:0",
                ];
            }
            if (in_array(Data::DAFR_DONOR_INFO_DOB, $personFields)) {
                $additionalDonorRules += [
                    "dob" => "required|date_format:m-d-Y",
                ];
            }
        }

        if (in_array(Data::DAFR_DONOR_INFO_PREFNAME, $personFields)) {
            $additionalDonorRules += [
                Data::DAFR_DONOR_INFO_PREFNAME => "nullable|min:3|max:32",
            ];
        }

        if (in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields) && !isset($_REQUEST['same_address'])) {
            $additionalDonorRules += [
                "mailing_address_1" => "required|min:3|max:32",
                "mailing_address_2" => "nullable|min:3|max:32",
                "mailing_city" => "required|min:1|max:32",
                "mailing_zip" => "required|digits:5|gt:0",
                "mailing_state" => "required",
                "mailing_country" => "required",
            ];
        }

        return $additionalDonorRules;
    }

    static public function title()
    {
        if (ClientInfo::isHGA()) {
            return "Additional Donor Advisor Information";
        } else {
            return "Additional Advisors";
        }
    }

    /**
     * @param $params
     * @return mixed
     */
    static public function getAdditionalDonorFromParams($params)
    {
        $personFields = Data::getAdditionalDonorInfoCustomFields();
        $paramsWithAddress = Data::getAddressWithParams($params);

        if (in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields)) {
            $addressWithMailAddress = Data::getMailingAddressWithParams($paramsWithAddress);
            return $addressWithMailAddress;
        }

       return $paramsWithAddress;
    }

}
