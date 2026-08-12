<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgGeographicArea extends Model
{
    /* @var string */
    protected $table = 'org_geographic_area';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $orgId
     * @return array
     */
    static public function getGeographicAreaIds($orgId=null)
    {
        $areaIds = OrgGeographicArea::where(['organization_id' => $orgId])
            ->orderBy('geographic_area_id')
            ->pluck('geographic_area_id')
            ->toArray();
        return $areaIds;
    }

    /**
     * @param $orgId
     * @return array
     */
    static public function getGeographicAreas($orgId=null)
    {
        $all = true;
        $areaIds = OrgGeographicArea::where(['organization_id' => $orgId])
            ->orderBy('geographic_area_id')
            ->pluck('geographic_area_id')
            ->toArray();
        // return $areaIds;

        $parents = GeographicArea::getAll();
        // return $parents;

        $data = [];
        foreach($parents as $parent) {
            $selected = $all;
            if (in_array($parent->geographic_area_id, $areaIds)) {
                $parent->selected = true;
                $selected = true;
            }

            if ($selected) {
                $data[] = $parent;
            }
        }
        return $data;
    }

}
