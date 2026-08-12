<?php

namespace App\Models;

class FundStatementHeldAway extends BaseModel
{
    /* @var string */
    protected $table = 'fund_statement_heldaway';

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
        return "statementHeldAway";
    }

    static public function getByFundId($fid)
    {
        return self::where('fund_id', 'ilike', $fid)->orderBy('created_date', 'DESC')->first();
    }

    // TODO: after feedback from Rajeev
    static public function getByFundIdAndThruDate($fid, $date)
    {
        if (!$date) {
            return self::where('fund_id', 'ilike', $fid)
                ->orderBy('created_date', 'DESC')->first();
        }
        return self::where('fund_id', 'ilike', $fid)
            ->whereDate('mv_date', '=', $date)
            ->orderBy('created_date', 'DESC')->first();

        // $query = self::where('fund_id', 'ilike', $fid);
        // return $query->orderBy('created_date', 'DESC')->first();
    }

}
