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
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\State;
use App\Models\User;

class DAFDonor {
    public function __construct() {
        $this->country = 'US';
        $this->state = State::getDefaultStateCode();
        $this->mailing_state = State::getDefaultStateCode();
        $this->same_address = true;

        // $time = strtotime("-40 year", time());
        // $this->dob = date("m-d-Y", $time);
        $this->dob = '';
    }

    public $fund_name;
    public $prefix;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $dob;
    public $ssn;
    public $preferred_name;
    public $citizenship;
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

    static public function title()
    {
        if (ClientInfo::isHGA()) {
            return "Fund Name & Primary Donor Advisor Information";
        } else {
            return "Fund & Account Info";
        }
    }

    public function rules()
    {
        $donorRules = [
            "fund_name" => "required|min:3|max:32",
            "dob" => "required|date_format:m-d-Y",
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

        $personFields = Data::getDonorInfoCustomFields();

        if (in_array(Data::DAFR_DONOR_INFO_SSN, $personFields)) {
            $donorRules += [
                "ssn" => "required|digits:9|gt:0",
            ];
        }

        if (in_array(Data::DAFR_DONOR_INFO_PREFNAME, $personFields)) {
            $donorRules += [
                Data::DAFR_DONOR_INFO_PREFNAME => "nullable|min:3|max:32",
            ];
        }

        if (in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields) && !isset($_REQUEST['same_address'])) {
            $donorRules += [
                "mailing_address_1" => "required|min:3|max:32",
                "mailing_address_2" => "nullable|min:3|max:32",
                "mailing_city" => "required|min:1|max:32",
                "mailing_zip" => "required|digits:5|gt:0",
                "mailing_state" => "required",
                "mailing_country" => "required",
            ];
        }

        return $donorRules;
    }

    /**
     * @param $params
     * @return mixed
     */
    static public function getDonorFromParams($params)
    {
        $personFields = Data::getDonorInfoCustomFields();
        $paramsWithAddress = Data::getAddressWithParams($params);

        if (in_array(Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields)) {
            $addressWithMailAddress = Data::getMailingAddressWithParams($paramsWithAddress);
            return $addressWithMailAddress;
        }

        return $paramsWithAddress;
    }

    /**
     * @param $contact
     * @return string
     */
    static public function createDAFDonorJsonFromContact(Contact $contact)
    {
        $donor = [];
        $donor["fund_name"] = '';
        $donor["prefix"] = $contact->prefix;
        $donor["first_name"] = $contact->first_name;
        $donor["middle_name"] = $contact->middle_name;
        $donor["last_name"] = $contact->last_name;
        $donor["suffix"] = $contact->suffix;
        $donor["dob"] = $contact->dob;
        $donor["ssn"] = $contact->ssn;
        $donor["preferred_name"] = $contact->informal;
        // $donor["citizenship = $contact->;

        $phone = $contact->getPrimaryPhone();
        // return $phone;

        if ($phone) {
            $donor["phone_number"] = $phone->phone_number;
            $donor["phone_type"] = $phone->phone_type;
        }
        $donor["email"] = User::getSessionUserEmail();

        /** @var ContactAddress $address */
        $address = $contact->getAddress();
        if ($address) {
            $donor["address_1"] = $address->address_1;
            $donor["address_2"] = $address->address_2;
            $donor["city"] = $address->city;
            $donor["state"] = $address->state;
            $donor["zip"] = $address->zip;
            $donor["country"] = $address->country;
        }
        $donor["same_address"] = true;
        $donor["contact_id"] = $contact->contact_id;

        $donor = Data::getAddressWithParams($donor);
        return json_encode($donor);
    }

}
