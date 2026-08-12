<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundRepType extends Model
{
    /* @var string */
    protected $table = 'fund_rep';

    /**
     * get Contact-Ids associated with a Fund-Id
     * @param $id
     * @return array
     */
    static public function getPTAContactIdsByFundId($id)
    {
        $contactIds = [];

        $pta = ['Donor', 'Head', 'DonorJ', 'RecD', 'RecJ', 'PTA'];

        // get all matching models (RepType = PTA)
        $models = FundRepType::where(['fund_id' => $id])->whereIn('rep_type', $pta)->get();

        // As per Rajeev
        // if (count($models) < 1) {
        //    $models = FundRepType::where(['fund_id' => ' ' . $id])->whereIn('rep_type', $pta)->get();
        // }

        foreach($models as $model) {
            $contactIds[] = $model->contact_id;
        }
        return $contactIds;
    }

    /* @var boolean */
    // public $timestamps = false;

}