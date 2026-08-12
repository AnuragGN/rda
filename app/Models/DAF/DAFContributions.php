<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2022
 * Time: 10:41 PM
 */

namespace App\Models\DAF;


class DAFContributions
{
   // public $ach_amount;
    public $wire_amount;
    public $wire_bank;
    public $check_amount;

    public $wire_pay;
    public $check_pay;

    public $securities;
    public $credit_card;
    public $stocks;
    public $others;

    public function rules()
    {
        return [
            "check_amount" => "required|numeric|min:1",
            "wire_amount" => "required|numeric|min:1",
            'wire_bank' => 'required|string|min:3',
        ];
    }

}