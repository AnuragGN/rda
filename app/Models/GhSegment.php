<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GhSegment extends Model
{
    /* @var string */
    protected $table = 'gh_segment';

    /* @var string */
    // protected $primaryKey = null;

    const MMP_SEGMENT_NAME = 'moneymarketpool';
    const HEP_SEGMENT_NAME = 'highequitypool';


    /* @var boolean */
    public $timestamps = false;

    /**
     * @return mixed
     */
    static public function getPools()
    {
        return self::where([
            'segment_level' => GhComposition::SEG_LEVEL_TWO
        ])->where('segment_label', 'ilike', '%pool%')->get();
    }

    /**
     * @return mixed
     */
    static public function getPoolTabs()
    {
        $models = self::where([
            'segment_level' => GhComposition::SEG_LEVEL_TWO
        ])->where('segment_label', 'ilike', '%pool%')->orderBy('segment_id', 'asc')->get();

        if (ClientInfo::isJCF() || ClientInfo::isGNA()) {
            foreach ($models as $key => $model) {
                if ($model->segment_name == GhSegment::MMP_SEGMENT_NAME || $model->segment_name == GhSegment::HEP_SEGMENT_NAME) {
                    $models->forget($key);
                }
            }
        }

        $tabs = [];
        foreach($models as $model) {
            $model->segment_label = str_replace(' Pool', '', $model->segment_label);
            $tabs[] = $model;
        }

        return $tabs;
    }

    static public function getBySegmentId($id)
    {
        return self::where(['segment_id' => $id])->first();
    }
}