<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 * interest_area_id: "A110",
 * description: null,
 * interest_area: "Health Care",
 * parent_interest_area_id: "A100"
 */
class InterestArea extends Model
{
    /* @var string */
    protected $table = 'interest_area';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function getAll()
    {
        $data = [];
        if (ClientInfo::isJCF() || ClientInfo::isHGA()) {
            $model = InterestArea::where(['parent_interest_area_id' => null])->orderBy('interest_area')->first();
            $models = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
            foreach ($models as $model) {
                $children = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
                $model->children = $children;
                $data[] = $model;
            }
        } else {
            $models = InterestArea::where(['parent_interest_area_id' => null])->orderBy('interest_area')->get();
            foreach ($models as $model) {
                $children = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
                $model->children = $children;
                $data[] = $model;
            }
        }
        return $data;
    }

    static public function TEST()
    {
        $data = [];
        if (ClientInfo::isJCF()) {
            $model = InterestArea::where(['parent_interest_area_id' => null])->orderBy('interest_area')->first();
            $models = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
            foreach ($models as $model) {
                $children = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
                $model->children = $children;
                $data[] = $model;
//                $item['parent'] = $model;
//                $item['children'] = $children;
//                $data[] = $item;
            }
        } else {
            $models = InterestArea::where(['parent_interest_area_id' => null])->orderBy('interest_area')->get();
            foreach ($models as $model) {
                $children = self::where(['parent_interest_area_id' => $model->interest_area_id])->get();
                $model->children = $children;
                $data[] = $model;
//                $item['parent'] = $model;
//                $item['children'] = $children;
//                $data[] = $item;
            }
        }
        return $data;
    }
}