<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundStatement extends BaseModel
{
    /* @var string */
    protected $table = 'fund_statement';

    /* @var string */
    // protected $primaryKey = 'fund_id'; DON'T UNCOMMENT IT - it will not return Fund Id.

    /* @var boolean */
    public $timestamps = false;

    public function getModelId()
    {
        return $this->fund_id;
    }

    public function isModelIdInteger() {
        return false;
    }

    public function getModelType()
    {
        return "statement";
    }

    static public function getById($id)
    {
        return self::where('fund_id', 'ilike', $id)
            ->orderBy('fund_statement_id', 'DESC')->first();
    }

    static public function getByIdAndThruDate($id, $thruDate)
    {
        if (!$thruDate) return self::getById($id);

        return self::where('fund_id', 'ilike', $id)
            ->whereDate('thru_date', '=', $thruDate)
            ->orderBy('fund_statement_id', 'DESC')->first();
        // ->orderBy('date_entered', 'DESC')->orderBy('fund_statement_id', 'DESC')->first();
    }

}
