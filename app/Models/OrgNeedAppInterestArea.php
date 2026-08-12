<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgNeedAppInterestArea extends Model
{
    /* @var string */
    protected $table = 'org_need_app_interest_area';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function TEST()
    {
        return self::where([])->limit(100)->orderBy('interest_area_id')->get();
    }

    /*SELECT count(*) FROM org_need_app ona,
                org_need_app_interest_area oia,organization o

                - WHERE org_need_app_interest_area.org_need_app_id = org_need_app.org_need_app_id
                - AND organization.organization_id = org_need_app.organization_id
                - AND o.visible = 'Y'
                - AND org_need_app_interest_area.interest_area_id = ?
                AND now() < org_need_app.campaign_end */

    static public function getProgramsCatalog()
    {
        $data = [];

        $limit = (env('APP_ENV') == 'dev') ? 4 : 100;
        if (ClientInfo::isJCF()) {
            $parent = InterestArea::whereNull('parent_interest_area_id')->orderBy('interest_area')->first();
            $models = InterestArea::where(['parent_interest_area_id' => $parent->interest_area_id])
                ->limit($limit)->orderBy('interest_area')->get();
        } else {
            $models = InterestArea::whereNull('parent_interest_area_id')
                ->limit($limit)->orderBy('interest_area')->get();
        }

        $date = today()->format('Y-m-d');

        foreach($models as $model) {
            // TODO: UndoGMF
            if (ClientInfo::isGMF()) {
                $count = OrgNeedAppInterestArea::where(['org_need_app_interest_area.interest_area_id' => $model->interest_area_id])
                    ->join('org_need_app', 'org_need_app_interest_area.org_need_app_id', '=', 'org_need_app.org_need_app_id')
                    ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
                    ->where(['organization.allow_recommendation' => 'Y'])
                    ->where('org_need_app.campaign_end', '>', $date)
                    ->count();
            } else {
                $count = OrgNeedAppInterestArea::where(['org_need_app_interest_area.interest_area_id' => $model->interest_area_id])
                    ->join('org_need_app', 'org_need_app_interest_area.org_need_app_id', '=', 'org_need_app.org_need_app_id')
                    ->join('organization', 'org_need_app.organization_id', '=', 'organization.organization_id')
                    ->where(['organization.visible' => 'Y'])
                    ->where('org_need_app.campaign_end', '>', $date)
                    ->count();
            }
            if ($count) {
                $data[] = [
                    'id' => $model->interest_area_id,
                    'name' => $model->interest_area,
                    'count' => $count
                ];
            }
        }
        return $data;
    }
}