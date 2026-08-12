<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPopulationServed extends Model
{
    /* @var string */
    protected $table = 'contact_population_served';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @param $contactId
     * @return array
     */
    static public function getPopulationServedIds($contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();
        $areaIds = ContactPopulationServed::where(['contact_id' => $contactId])
            ->orderBy('population_served_id')
            ->pluck('population_served_id')
            ->toArray();
        return $areaIds;
    }

    /**
     * @param $contactId
     * @return array
     */
    static public function getPopulationServed($all=false, $contactId=null)
    {
        if (!$contactId) $contactId = Contact::sessionContactId();

        $areaIds = ContactPopulationServed::where(['contact_id' => $contactId])
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

    /**
     * @param $ids
     * @return bool
     */
    static public function saveContactPopulationServed($ids)
    {
        $contactId = Contact::sessionContactId();
        if (!$contactId) return false;
        ContactPopulationServed::where(['contact_id' => $contactId])->delete();

        if (!$ids || !count($ids)) return true;
        foreach($ids as $id) {
            $model = new ContactPopulationServed();
            $model->contact_id = $contactId;
            $model->population_served_id = $id;
            $model->save();
        }
        return true;
    }

}
