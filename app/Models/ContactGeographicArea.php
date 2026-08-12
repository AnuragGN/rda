<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactGeographicArea extends Model
{
    /* @var string */
    protected $table = 'contact_geographic_area';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $contactId
     * @return array
     */
    static public function getGeographicAreaIds($contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();
        $areaIds = ContactGeographicArea::where(['contact_id' => $contactId])
            ->orderBy('geographic_area_id')
            ->pluck('geographic_area_id')
            ->toArray();
        return $areaIds;
    }

    /**
     * @param $contactId
     * @return array
     */
    static public function getGeographicAreas($all=false, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        $areaIds = ContactGeographicArea::where(['contact_id' => $contactId])
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

    /**
     * @param $ids
     * @return bool
     */
    static public function saveContactGeographicAreas($ids)
    {
        $contactId = Contact::sessionContactId();
        if (!$contactId) return false;
        ContactGeographicArea::where(['contact_id' => $contactId])->delete();

        if (!$ids || !count($ids)) return true;
        foreach($ids as $id) {
            $model = new ContactGeographicArea();
            $model->contact_id = $contactId;
            $model->geographic_area_id = $id;
            $model->save();
        }
        return true;
    }

}
