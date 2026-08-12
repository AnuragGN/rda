<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneType extends Model
{
    /* @var string */
    protected $table = 'phone_type';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function getContactPhoneTypePrimary() {
        $conditions = [
            'is_primary' => 'Y',
            'is_org_phone' => 'N'
        ];
        $model = self::where($conditions)->first();
        return ($model ? $model->phone_type : null);
    }

    static public function isContactPhoneTypePrimary($type) {
        return self::getContactPhoneTypePrimary() == $type;
    }

    static public function getContactPhoneTypes() {
        $conditions = [
            'is_org_phone' => 'N'
        ];
        return self::where($conditions)->get();
    }

    static public function getContactPhoneTypeByType($type) {
        $conditions = [
            'is_org_phone' => 'N',
            'phone_type' => $type
        ];
        $model = self::where($conditions)->first();
        if (!$model) return null;
        if(!isset($model->label)) $model->label = ucfirst($model->phone_type);
        return $model;
    }

    static public function getContactPhoneTypeLabel($type) {
        $conditions = [
            'is_org_phone' => 'N',
            'phone_type' => $type
        ];
        $model = self::where($conditions)->first();
        if (!$model) return '';
        return isset($model->label) ? $model->label : ucfirst($model->phone_type);
    }

    static public function selectContactPhoneTypes() {
        $conditions = [
            'is_org_phone' => 'N',
        ];
        $models = self::where($conditions)->pluck('phone_type', 'phone_type');
        return array_merge(['' => 'Phone Type'], $models->toArray());
    }

    static public function selectDAFContactPhoneTypes() {
        $conditions['is_org_phone'] = 'N';
        if (!ClientInfo::isHGA()) $conditions['is_primary'] = 'Y';
        $models = self::where($conditions)->pluck('label', 'phone_type');
        return $models;
    }

    /**
     * @return null
     */
    static public function getPrimaryPhoneTypeOrAny()
    {
        $primaryPhoneType = self::getContactPhoneTypePrimary();

        // If no primary phone type is found, get the phone type of the first available entry
        if (!$primaryPhoneType) {
            $firstType = self::getContactPhoneTypes()->first();
            if ($firstType) {
                $primaryPhoneType = $firstType->phone_type;
            }
        }

        return $primaryPhoneType;
    }


    /****************************************************************
     * Organization
     ****************************************************************/
    static public function getOrgPhoneTypePrimary() {
        $conditions = [
            'is_primary' => 'Y',
            'is_org_phone' => 'Y'
        ];
        $model = self::where($conditions)->first();
        return ($model ? $model->phone_type : null);
    }

    static public function isOrgPhoneTypePrimary($type) {
        return self::getOrgPhoneTypePrimary() == $type;
    }

    static public function getOrgPhoneTypes() {
        $conditions = [
            'is_org_phone' => 'Y'
        ];
        return self::where($conditions)->get();
    }

    static public  function getOrgPhoneTypeByType($type) {
        $conditions = [
            'phone_type' => $type,
            'is_org_phone' => 'Y'
        ];
        return self::where($conditions)->first();
    }

    static public function isValidOrgPhoneType($type) {
        if (!$type) return false;
        $conditions = [
            'phone_type' => $type,
            'is_org_phone' => 'Y'
        ];
        return self::where($conditions)->exists();
    }

    static public function getAll() {
        return self::all();
    }

}