<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgPopulationServed extends Model
{
    /* @var string */
    protected $table = 'org_population_served';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $contactId
     * @return array
     */
    static public function getPopulationServedIds($orgId=null)
    {
        $areaIds = ContactPopulationServed::where(['organization_id' => $orgId])
            ->orderBy('population_served_id')
            ->pluck('population_served_id')
            ->toArray();
        return $areaIds;
    }

    /**
     * @param $contactId
     * @return array
     */
    static public function getPopulationServed($orgId=null)
    {
        $all = true;
        $areaIds = OrgPopulationServed::where(['organization_id' => $orgId])
            ->orderBy('population_served_id')
            ->pluck('population_served_id')
            ->toArray();
        // return $areaIds;

        $parents = PopulationServed::getAll();
        // return $parents;

        $data = [];
        foreach($parents as $parent) {
            $selected = $all;
            if (in_array($parent->population_served_id, $areaIds)) {
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
