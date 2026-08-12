<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 24-10-2019
 * Time: 17:02
 */

namespace App\Models;


class GrantForm
{
    public $fundId;
    public $organizationId;
    public $amount;
    public $purpose;
    public $note;
    public $anonymous;

    /**
     * @return string
     */
    static public function frequencyLabel()
    {
        if (ClientInfo::isHGA()) {
            return "Frequency";
        } else {
            return "Granting Frequency";
        }
    }

    static public function cartLabel()
    {
        if (ClientInfo::isHGA()) {
            return "My Grants";
        } else if (ClientInfo::isCCT()) {
            return "Grant Basket";
        } else {
            return "My Cart";
        }
    }
}