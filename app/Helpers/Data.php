<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/27/2021
 * Time: 4:52 PM
 */

namespace App\Helpers;


use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\DAFAccount;

class Data
{

    // delete - not required
    const GRANTING_FREQUENCIES = [
        'once',
        'monthly',
        'quarterly',
        'annual'
    ];

    const GRANTING_FREQUENCY_ONCE = 'once';

    const DAFR_DONOR_INFO_PREFIX = "prefix";
    const DAFR_DONOR_INFO_SSN = "ssn";
    const DAFR_DONOR_INFO_DOB = "dob";
    const DAFR_DONOR_INFO_SUFFIX = "suffix";
    const DAFR_DONOR_INFO_PREFNAME = "preferred_name";
    const DAFR_DONOR_INFO_CITIZENSHIP = "citizenship";
    const DAFR_DONOR_INFO_ADVISOR_RELATIONSHIP = "relation";
    const DAFR_DONOR_INFO_FUND_PRIVILEGES = "fund_privileges";

    const DAFR_DONOR_INFO_FUND_PRIVILEGE_FULL = "full";
    const DAFR_DONOR_INFO_FUND_PRIVILEGE_VIEW = "view";
    const DAFR_DONOR_INFO_FUND_PRIVILEGE_GRANT = "grant";

    const DAFR_DONOR_INFO_MAILING_ADDRESS = 'mailing_address';
    const DAFR_DONOR_INFO_TOOLTIP = "tooltip";

    const DAFR_DONOR_CONTRIBUTIONS_STOCKS = "stocks";
    const DAFR_DONOR_CONTRIBUTIONS_OTHERS = "others";

    // OBSOLETE: This method is not being used now!
    static public function getDAFRegistrationDonorInfo()
    {
        if (ClientInfo::isHGA()) {

            $DAFRDonorInfo = [
                'prefix',
                'ssn',
                'dob',
                'suffix',
                'preferred_name',
                'citizenship',
                'relation',
                'fund_privileges',
                'contact_name',
                'mailing_address',
                'tooltip',
                'stocks',
                'contributions_others'
            ];
        } else {
            $DAFRDonorInfo = [
                'mailing_address',
            ];
        }

        return $DAFRDonorInfo;
    }

    /**
     * Granting frequency for JCF
     * @return array
     */
    static public function selectableGrantingFrequencies()
    {
        // 'once' is required!
        if (ClientInfo::isJCF()) {
            return [
                'once' => 'One Time',
                'monthly' => 'Automated Monthly',
                'quarterly' => 'Automated Quarterly',
                'annual' => 'Automated Annually'
            ];
        } else if (ClientInfo::isCCTorNTC()) {
            return [
                'once' => 'One Time',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly',
                'semi-annual' => 'Semi-Annually',
                'annual' => 'Annually'
            ];
        } else if (ClientInfo::isMercy()) {
            return [
                'once' => 'One Time',
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly'
            ];
        };
        return [
            'once' => 'One Time',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annual' => 'Annually'
        ];
    }

    /**
     * get a valid granting frequency display label
     *
     * @param $key
     * @return mixed
     */
    static public function displayableGrantingFrequency($key)
    {
        $values = self::selectableGrantingFrequencies();
        return isset($values[$key]) ? $values[$key] : $values[self::GRANTING_FREQUENCY_ONCE];
    }

    /**
     * get a valid granting frequency key
     *
     * @param $key
     * @return string
     */
    static public function getGrantingFrequency($key)
    {
        // delete - not required
        // if (in_array($key, self::GRANTING_FREQUENCIES))
        //     return $key;
        // return 'once';

        $values = self::selectableGrantingFrequencies();
        return isset($values[$key]) ? $key : self::GRANTING_FREQUENCY_ONCE;
    }

    static public function getAddressWithParams($params)
    {
        $address = [
            'address_1' => $params['address_1'],
            'address_2' => $params['address_2'],
            'city' => $params['city'],
            'zip' => $params['zip'],
            'state' => $params['state'],
            'country' => $params['country'],
        ];

        unset($params['address_1']);
        unset($params['address_2']);
        unset($params['city']);
        unset($params['zip']);
        unset($params['state']);
        unset($params['country']);

        $params['address'] = $address;

        return $params;
    }

    static public function getMailingAddressWithParams($params)
    {
        if( isset($params['same_address'])) {
            $mAddress = $params['address'];
        } else {
            $mAddress = [
                'address_1' => $params['mailing_address_1'],
                'address_2' => $params['mailing_address_2'],
                'city' => $params['mailing_city'],
                'zip' => $params['mailing_zip'],
                'state' => $params['mailing_state'],
                'country' => $params['mailing_country'],
            ];
        }

        unset($params['mailing_address_1']);
        unset($params['mailing_address_2']);
        unset($params['mailing_city']);
        unset($params['mailing_zip']);
        unset($params['mailing_state']);
        unset($params['mailing_country']);

        $params['mailing_address'] = $mAddress;

        return $params;
    }

    static private function nullAddress()
    {
        return [
            'address_1' => '',
            'address_2' => '',
            'city' => '',
            'zip' => '',
            'state' => '',
            'country' => '',
        ];
    }

    static public function copyAddress($object, $address)
    {
        $object->address_1 = $address['address_1'];
        $object->address_2 = $address['address_2'];
        $object->city = $address['city'];
        $object->zip = $address['zip'];
        $object->state = $address['state'];
        $object->country = $address['country'];
        return $object;
    }

    static public function copyMailingAddress($object, $address)
    {
        $object->mailing_address_1 = $address['address_1'];
        $object->mailing_address_2 = $address['address_2'];
        $object->mailing_city = $address['city'];
        $object->mailing_zip = $address['zip'];
        $object->mailing_state = $address['state'];
        $object->mailing_country = $address['country'];
        return $object;
    }

    /**
     * @param $object
     * @param $data
     * @param bool|false $mailingAddress
     * @return mixed
     */
    static public function setAddressInObject($object, $data, $mailingAddress=false)
    {
        if (!$data) return $object;

        // set main address
        $address = isset($data['address']) ? $data['address'] : Data::nullAddress();
        $object = Data::copyAddress($object, $address);

        if ($mailingAddress) {
            if ( isset($data['same_address'])) {
                $object->mailing_address = null;
                $object->same_address = true;
            } else {
                $address = isset($data['mailing_address']) ? $data['mailing_address'] : Data::nullAddress();
                $object = Data::copyMailingAddress($object, $address);
                $object->same_address = false;
            }
        }

        return $object;
    }

    /**
     * @param $data
     * @param bool|false $mailingAddress
     * @return array|\League\CommonMark\Extension\CommonMark\Node\Block\ListData|mixed|null|string|static
     */
    static public function setAddressWithModel($data, $mailingAddress=false)
    {
        $combinedInfo = [];
        if ($data) {
            if (isset($data['address'])) {
                $address = $data['address'];
            } else {
                $address = Data::nullAddress();
            }
            $combinedInfo = array_merge($data, $address);
        }

        if ($mailingAddress) {

            if ( isset($data['same_address'])) {
                $mAddress = $data['address'];
                unset($combinedInfo['mailing_address']);

            } else {
                if ( isset($data['mailing_address'])) {
                    $mAddress = $data['mailing_address'];

                    unset($combinedInfo['mailing_address']);
                    //unset($data['mailing_address']);
                } else {
                    $mAddress = Data::nullAddress();
                }
            }

            foreach ($mAddress as $key => $value) {
                $mAddress['mailing_'.$key] = $value;
            }
            $combinedInfo += $mAddress;
        }

        unset($combinedInfo['address']);

        return $combinedInfo;
    }

    /**
     * @param $ssn
     * @return string
     */
    static public function setSSNStar($ssn)
    {
        $ssnStar = "***-**-".substr($ssn, -4);
        return $ssnStar;
    }

    /**
     * @param $ssn
     * @return mixed
     */
    static public function formatSSN($ssn)
    {
        $numbers_only = preg_replace("/[^\d]/", "", $ssn);
        $ssn = preg_replace("/^1?(\d{3})(\d{2})(\d{4})$/", "$1-$2-$3", $numbers_only);
        return $ssn;
    }

    /**
     * @param $phone
     * @return mixed
     */
    static public function formatDAFPhone($phone)
    {
        $numbers_only = preg_replace("/[^\d]/", "", $phone);
        $phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
        return $phone;
    }

    /**
     * @param $ein
     * @return mixed
     */
    static public function formatOrgEin($ein)
    {
        $numbers_only = preg_replace("/[^\d]/", "", $ein);
        $ein = preg_replace("/^1?(\d{2})(\d{7})$/", "$1-$2", $numbers_only);
        return $ein;
    }

    /**
     * @param $privilegesKey
     * @return mixed
     */
    static public function getFundPrivilegeName($privilegesKey)
    {
        $privilegesList = DAFAccount::getDonorFundPrivilegesList();
        $privilegeFullName = $privilegesList[$privilegesKey];

        return $privilegeFullName;
    }

    /**
     * @param $citizenshipKey
     * @return mixed
     */
    static public function getCitizenshipName($citizenshipKey)
    {
        $citizenshipList = DAFAccount::getDonorCitizenshipList();
        $citizenshipFullName = $citizenshipList[$citizenshipKey];

        return $citizenshipFullName;
    }

    static public function getRelationshipName($relationshipKey)
    {
        $relationshipList = Data::getDonorRelationshipList();
        $citizenshipFullName = $relationshipList[$relationshipKey];

        return $citizenshipFullName;
    }

    /**
     * @return array
     */
    static public function getDonorInfoCustomFields()
    {
        // dob is mandatory field
        if (ClientInfo::isHGA()) {
            return [
                'prefix',
                'suffix',
                'preferred_name',
                'fund_privileges',
                'dob',
                'ssn',
                'citizenship',
                'mailing_address',
            ];
        }

        return [
            'prefix',
            'dob',
            'mailing_address',
        ];
    }

    /**
     * @return array
     */
    static public function getAdditionalDonorInfoCustomFields()
    {
        if (ClientInfo::isHGA()) {
            return [
                'prefix',
                'suffix',
                'preferred_name',
                'fund_privileges',
                'dob',
                'ssn',
                'citizenship',
                'relation',
                'mailing_address',
            ];
        }

        return [
            'prefix',
            'fund_privileges',
            'dob',
            'relation',
            'mailing_address',
        ];
    }

    /**
     * @return array
     */
    static public function getSuccessorsIndividualCustomFields()
    {
        if (ClientInfo::isHGA()) {
            return [
                'prefix',
                'suffix',
                'dob',
                'ssn',
                'relation',
                // 'citizenship',
                // 'preferred_name',
                // 'fund_privileges',
            ];
        }

        return [
            'prefix',
            'dob',
            'relation',
        ];
    }

    /**
     * @return array
     */
    static public function getContributionTypes()
    {
        if (ClientInfo::isHGA()) {
            return [
                'stocks',
                'others'
            ];
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    static public function getDonorRelationshipList()
    {
        return [
            // null => "Select...",
            "spouse" => "Spouse",
            "mother" => "Mother",
            "father" => "Father",
            "daughter" => "Daughter",
            "son" => "Son",
            "sister" => "Sister",
            "brother" => "Brother",
            "niece" => "Niece",
            "nephew" => "Nephew",
            "granddaughter" => "Granddaughter",
            "grandson" => "Grandson",
            "financial_advisor" => "Financial Advisor",
            "cpa" => "CPA",
            "attorney" => "Attorney",
            "other" => "Other"
        ];
    }

    /**
     * for CCT Grant History
     * @return array
     */
    static public function getGrantHistoryStatuses()
    {
        return [
            'all' => 'All',
            'approved' => 'Approved',
            'granted' => 'Granted',
            'canceled' => 'Canceled',
        ];
    }

    static public function getSelectableDocumentPeriod()
    {
        return [
            'all' => 'All Periods',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annual' => 'Annual',
        ];
    }

    /**
     * daf types
     * @return array
     */
    static public function getDAFTypes()
    {
        //if (ClientInfo::isPFR()) {
            return [
                'traditional_daf' => 'Traditional DAF',
                'spending_policy_daf' => 'Spending Policy DAF',
                'render_daf' => 'Render DAF',
            ];
        //}
        //return [];
    }

    /**
     * @param $dafTypeKey
     * @return null|string
     */
    public static function getDAFTypeLabel($dafTypeKey)
    {
        if (! ClientConfig::feature('DAF_REG_DAF_TYPE') || empty($dafTypeKey)) {
            return null;
        }

        $dafTypes = self::getDAFTypes();

        // Check if the provided DAF type key exists in the DAF types array
        if (array_key_exists($dafTypeKey, $dafTypes)) {
            return $dafTypes[$dafTypeKey];
        }

        return '';
    }

}
