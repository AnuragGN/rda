<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 2/2/2023
 * Time: 10:50 PM
 */

namespace App\Models\DAF;


use App\Models\ClientInfo;

class DAFInvestment
{
    // public function __construct() {}

    static public function title()
    {
        if (ClientInfo::isHGA()) {
            return "Investment Fund Options";
        } else {
            return "Investment Pools";
        }
    }

    static public function poolTitle()
    {
        if (ClientInfo::isHGA()) {
            return "Fund Name";
        } else {
            return "Pool Name";
        }
    }

}
