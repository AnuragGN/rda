<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgNeedAppPopulationServed extends Model
{
    /* @var string */
    protected $table = 'org_need_app_population_served';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $orgNeedAppId
     * @return array
     */
    static public function getPopulationServedIds($orgNeedAppId=null)
    {
        $areaIds = OrgNeedAppPopulationServed::where(['org_need_app_id' => $orgNeedAppId])
            ->orderBy('population_served_id')
            ->pluck('population_served_id')
            ->toArray();
        return $areaIds;
    }

}
