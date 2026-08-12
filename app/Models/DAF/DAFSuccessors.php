<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2022
 * Time: 10:50 PM
 */

namespace App\Models\DAF;


use App\Models\ClientInfo;

class DAFSuccessors
{
    public function __construct() {
//        $this->isNew = true;
//        $this->key = intval(microtime(true) * 1000);
    }

    public $endowment;

    static public function title()
    {
        if (ClientInfo::isHGA()) {
            return "Succession Strategies";
        } else {
            return "Successor Designation";
        }
    }

    static public function titleEndowmentReview()
    {
        if (ClientInfo::isHGA()) {
            return "Succession Strategies";
        } else {
            return "Successor Designation - Review";
        }
    }

}