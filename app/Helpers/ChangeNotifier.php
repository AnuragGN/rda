<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 12/29/2021
 * Time: 2:11 PM
 */

namespace App\Helpers;


use App\Models\AddressType;
use App\Models\ContactAddress;
use App\Models\ContactPhone;
use App\Models\Email;
use App\Models\PhoneType;

class ChangeNotifier
{

    /**
     * @param ContactAddress $address
     */
    static public function onContactAddressDelete(ContactAddress $address)
    {
        $typeLabel = AddressType::getContactAddressTypeLabel($address->address_type);
        $title = "Contact Address (" . $typeLabel . ") Deleted";
        $info = [
            'Address: ' => $address->getAddressInline()
        ];
        Email::objectDeletedNotification($title, $info);
    }

    /**
     * @param ContactPhone $phone
     */
    static public function onContactPhoneDelete(ContactPhone $phone)
    {
        $typeLabel = PhoneType::getContactPhoneTypeLabel($phone->phone_type);
        $title = "Contact Phone (" . $typeLabel . ") Deleted";
        $info = [
            'Phone Number: ' => $phone->phone_number
        ];
        Email::objectDeletedNotification($title, $info);
    }

    /**
     * @param $original
     * @param $updated
     * @return array
     */
    static public function onContactAddressUpdate($original, $updated)
    {
        $typeLabel = AddressType::getContactAddressTypeLabel($updated->address_type);
        $title = "Contact Address (" . $typeLabel . ") Updated";

        $changes = [];
        if ($original->address_1 != $updated->address_1) {
            $changes[] = ['key' => 'Address Line 1', 'from' => $original->address_1, 'to' => $updated->address_1];
        }
        if ($original->address_2 != $updated->address_2) {
            $changes[] = ['key' => 'Address Line 2', 'from' => $original->address_2, 'to' => $updated->address_2];
        }
        if ($original->city != $updated->city) {
            $changes[] = ['key' => 'City', 'from' => $original->city, 'to' => $updated->city];
        }
        if ($original->state != $updated->state) {
            $changes[] = ['key' => 'State', 'from' => $original->state, 'to' => $updated->state];
        }
        if ($original->zip != $updated->zip) {
            $changes[] = ['key' => 'ZIP', 'from' => $original->zip, 'to' => $updated->zip];
        }
        if ($original->country != $updated->country) {
            $changes[] = ['key' => 'Country', 'from' => $original->country, 'to' => $updated->country];
        }

        if (count($changes)) {
            Email::objectUpdatedNotification($title, $changes);
        }
        return $changes;
    }

    /**
     * @param $original
     * @param $updated
     * @return array
     */
    static public function onContactPhoneUpdate($original, ContactPhone $updated)
    {
        $typeLabel = PhoneType::getContactPhoneTypeLabel($updated->phone_type);
        $title = "Contact Phone (" . $typeLabel  . ") Updated";
        $changes = [];
        if ($original->phone_number != $updated->phone_number) {
            $changes[] = ['key' => 'Phone Number', 'from' => $original->phone_number, 'to' => $updated->phone_number];
        }
        if (count($changes)) {
            Email::objectUpdatedNotification($title, $changes);
        }
        return $changes;
    }

    /**
     * @param $original
     * @param $updated
     * @return array
     */
    static public function onContactProfileUpdate($original, $updated)
    {
        $title = "Contact Profile Updated";

        $changes = [];
        if ($original->prefix != $updated->prefix) {
            $changes[] = ['key' => 'Prefix', 'from' => $original->prefix, 'to' => $updated->prefix];
        }
        if ($original->first_name != $updated->first_name) {
            $changes[] = ['key' => 'First Name', 'from' => $original->first_name, 'to' => $updated->first_name];
        }
        if ($original->last_name != $updated->last_name) {
            $changes[] = ['key' => 'Last Name', 'from' => $original->last_name, 'to' => $updated->last_name];
        }
        if ($original->suffix1 != $updated->suffix1) {
            $changes[] = ['key' => 'Suffix', 'from' => $original->suffix1, 'to' => $updated->suffix1];
        }
        if ($original->company_name != $updated->company_name) {
            $changes[] = ['key' => 'Company Name', 'from' => $original->company_name, 'to' => $updated->company_name];
        }
        if ($original->web_site != $updated->web_site) {
            $changes[] = ['key' => 'Web Site', 'from' => $original->web_site, 'to' => $updated->web_site];
        }
        if (count($changes)) {
            Email::objectUpdatedNotification($title, $changes);
        }
        return $changes;
    }

}