<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInterestArea extends Model
{
    /* @var string */
    protected $table = 'contact_interest_area';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    static public function getInterestAreaIds($contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();
        $areaIds = ContactInterestArea::where(['contact_id' => $contactId])
            ->orderBy('interest_area_id')
            ->pluck('interest_area_id')
            ->toArray();
        return $areaIds;
    }

    /**
     * @param $contactId
     * @return array
     */
    static public function getInterestAreas($all=false, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        $areaIds = ContactInterestArea::where(['contact_id' => $contactId])
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

    /**
     * @param $ids
     * @return bool
     */
    static public function saveContactInterests($ids)
    {
        $contactId = Contact::sessionContactId();
        if (!$contactId) return false;
        ContactInterestArea::where(['contact_id' => $contactId])->delete();

        if (!$ids || !count($ids)) return true;
        foreach($ids as $id) {
            $model = new ContactInterestArea();
            $model->contact_id = $contactId;
            $model->interest_area_id = $id;
            $model->save();
        }
        return true;
    }

}
