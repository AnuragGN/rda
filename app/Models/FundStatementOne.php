<?php

namespace App\Models;

class FundStatementOne extends BaseModel
{
    /* @var string */
    protected $table = 'fund_statement_1';

    /* @var boolean */
    public $timestamps = false;

    public function getModelId()
    {
        return $this->id;
    }

    public function isModelIdInteger()
    {
        return false;
    }

    public function getModelType()
    {
        return "statementOne";
    }

    static public function getByStatementId($sid)
    {
        return self::where(['fund_statementid' => $sid])->first();
    }

}
