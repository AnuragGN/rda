<?php

namespace App\Forms;

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 16-05-2023
 * Time: 18:36
 */
class FormFundHistoryFilter
{
    public $fund_id;
    public $interest_area;
    public $status;
    public $amount_min;
    public $amount_max;

    public function __construct(){}

    public function set($fundId, $params)
    {
        if (isset($params['fund_id']) && !empty($params['fund_id'])) {
            $this->fund_id = $params['fund_id'];
        } else {
            $this->fund_id = $fundId;
        }
        $this->interest_area = isset($params['interest_area']) ? $params['interest_area'] : 'all';
        $this->status = isset($params['status']) ? $params['status'] : 'all';
        $this->amount_min = isset($params['amount_min']) ? $params['amount_min'] : 'all';
        $this->amount_max = isset($params['amount_max']) ? $params['amount_max'] : 'all';
    }
}