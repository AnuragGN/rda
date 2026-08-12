<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2022
 * Time: 10:50 PM
 */

namespace App\Models\DAF;


use App\Models\ClientInfo;

class DAFStocks
{
    public function __construct() {
        $this->isNew = true;
        $this->key = intval(microtime(true) * 1000);
    }

    public $share_name;
    public $shares;
//    public $isNew;
//    public $key;

    public function rules()
    {
        $securityRules = [
            "stock_name" => "required|string|min:3|max:32",
            "shares" => "required|min:1|digits_between:1,10",
        ];

        return $securityRules;
    }

}