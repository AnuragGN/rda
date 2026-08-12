<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2022
 * Time: 10:50 PM
 */

namespace App\Models\DAF;


use App\Models\ClientInfo;

class DAFSecurity
{
    public function __construct() {
        $this->isNew = true;
        $this->key = intval(microtime(true) * 1000);
    }

    public $key;
    public $security_name;
    public $name;
    public $account_number;
    public $custodian_name;
    public $shares;
    public $amount;

    public function rules()
    {
        $securityRules = [
            "fund_name" => "required|string|min:3|max:32",
            "name" => "required|string|min:3|max:32",
            'custodian_name' => "required|string|min:3|max:32",
            "account_number" => "required|digits_between:5,20",
            "shares" => "required|min:1|digits_between:1,10|gt:0",
            "amount" => "required|numeric|min:1",
        ];

        if (ClientInfo::isHGA()) unset($securityRules['custodian_name']);

        return $securityRules;
    }

}