<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgNeedAppGeographicArea extends Model
{
    /* @var string */
    protected $table = 'org_need_app_geographic_area';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $orgNeedAppId
     * @return array
     */
    static public function getGeographicAreaIds($orgNeedAppId=null)
    {
        $areaIds = OrgNeedAppGeographicArea::where(['organization_id' => $orgNeedAppId])
            ->orderBy('geographic_area_id')
            ->pluck('geographic_area_id')
            ->toArray();
        return $areaIds;
    }

}
