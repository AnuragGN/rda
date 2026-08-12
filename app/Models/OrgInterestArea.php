<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgInterestArea extends Model
{
    /* @var string */
    protected $table = 'org_interest_area';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    /**
     * @param $orgId
     * @return array
     */
    static public function getInterestAreaIds($orgId=null)
    {
        $areaIds = OrgInterestArea::where(['organization_id' => $orgId])
            ->orderBy('interest_area_id')
            ->pluck('interest_area_id')
            ->toArray();
        return $areaIds;
    }

    static public function getOrganizationCatalog()
    {
        $data = [];

        $limit = (env('APP_ENV') == 'dev') ? 2 : 200;
        if (ClientInfo::isJCF()) {
            $parent = InterestArea::whereNull('parent_interest_area_id')->orderBy('interest_area')->first();
            $models = InterestArea::where(['parent_interest_area_id' => $parent->interest_area_id])
                ->limit($limit)->orderBy('interest_area')->get();
        } else {
            $models = InterestArea::where(['parent_interest_area_id' => null])
                ->limit($limit)->orderBy('interest_area')->get();
        }

        foreach($models as $model) {
            $total = 0;
            $items = [];
            $conditions = [];

            // TODO: UndoGMF
            if (ClientInfo::isGMF()) {
                $conditions = ['organization.allow_recommendation' => 'Y'];
            } else {
                $conditions = ['organization.allow_recommendation' => 'Y', 'organization.visible' => 'Y'];
            }
            // parent
            $count = OrgInterestArea::where(['org_interest_area.interest_area_id' => $model->interest_area_id])
                ->join('organization', 'organization.organization_id', '=', 'org_interest_area.organization_id')
                ->where($conditions)
                ->count();
            if ($count) {
                $items[] = [
                    'id' => $model->interest_area_id,
                    'name' => $model->interest_area,
                    'count' => $count
                ];
                $total += $count;
            }

            // children
            $children = InterestArea::where(['parent_interest_area_id' => $model->interest_area_id])->get();
            foreach($children as $child) {
                $count = OrgInterestArea::where(['org_interest_area.interest_area_id' => $child->interest_area_id])
                    ->join('organization', 'organization.organization_id', '=', 'org_interest_area.organization_id')
                    ->where($conditions)
                    ->count();
                if ($count) {
                    $items[] = [
                        'id' => $child->interest_area_id,
                        'name' => $child->interest_area,
                        'count' => $count
                    ];
                    $total += $count;
                }
            }
            if ($items) {
                $data[$model->interest_area] = ['id' => $model->interest_area_id, 'total' => $total, 'items' => $items];
            }
        }
        return $data;
    }

    /**
     * @param $orgId
     * @return array
     */
    static public function getInterestAreas($orgId)
    {
        $all = true;
        $areaIds = OrgInterestArea::where(['organization_id' => $orgId])
            ->orderBy('interest_area_id')
            ->pluck('interest_area_id')
            ->toArray();
        // return $areaIds;

        $parents = InterestArea::getAll();
        // return $parents;

        $data = [];
        foreach($parents as $parent) {
            $selected = $all;
            if (in_array($parent->interest_area_id, $areaIds)) {
                $parent->selected = true;
                $selected = true;
            }

            $children = [];
            foreach($parent->children as $child) {
                if (in_array($child->interest_area_id, $areaIds)) {
                    $child->selected = true;
                    $selected = true;
                }
                if ($all || $child->selected) {
                    $children[] = $child;
                }
            }
            if ($selected) {
                $parent->children = $children;
                $data[] = $parent;
            }
        }
        return $data;
    }

    static public function TEST()
    {
        return self::where([])->limit(100)->get();
    }

    static public function getPrimaryInterestName($orgId)
    {
        $area = OrgInterestArea::where(['organization_id' => $orgId])
            ->join('interest_area', 'interest_area.interest_area_id', '=', 'org_interest_area.interest_area_id')
            ->where('interest_area.parent_interest_area_id', 100)
            // ->whereNull('interest_area.parent_interest_area_id')
            ->select('interest_area.interest_area')->first(); // pluck('interest_area.interest_area', 'interest_area.interest_area_id');
        return $area ? $area->interest_area : "";
    }

    static public function addOrgInterestAreas()
    {
        $areas = [
            25 => "A100",
            34 => "B100",
            35 => "A100",
            36 => "B100",
            560 => "A100",
            563 => "B100",
            570 => "C100",
            571 => "D100",
            572 => "E100",
            573 => "F100",
            576 => "G100",
            580 => "H100",
            583 => "I100",
            587 => "J100",
            588 => "K100",
        ];

        $data = [];
        foreach($areas as $area => $id) {
            $model = new OrgInterestArea();
            $model->interest_area_id = $id;
            $model->organization_id = $area;
            $model->save();
            $data[] = $model;
        }
        return $data;
    }
}