<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    /* @var string */
    protected $table = 'address_type';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function getContactAddressTypePrimary() {
        $conditions = [
            'is_primary' => 'Y',
            'is_org_address' => 'N'
        ];
        $model = self::where($conditions)->first();
        return ($model ? $model->address_type : null);
    }

    static public function isContactAddressTypePrimary($type) {
        return self::getContactAddressTypePrimary() == $type;
    }

    static public function getContactAddressTypes() {
        $conditions = ['is_org_address' => 'N'];
        return self::where($conditions)->get();
    }

    static public function isValidContactAddressType($type) {
        if (!$type) return false;
        $conditions = [
            'address_type' => $type,
            'is_org_address' => 'N'
        ];
        return self::where($conditions)->exists();
    }

    static public  function getContactAddressTypeByType($type) {
        $conditions = [
            'address_type' => $type,
            'is_org_address' => 'N'
        ];
        return self::where($conditions)->first();
    }

    static public function getContactAddressTypeLabel($type)
    {
        $model = self::getContactAddressTypeByType($type);
        if (!$model) return '';
        return isset($model->label) ? $model->label : ucfirst($model->address_type);
    }

    /****************************************************************
     * Organization
     ****************************************************************/
    static public function getOrgAddressTypePrimary() {
        $conditions = [
            'is_primary' => 'Y',
            'is_org_address' => 'Y'
        ];
        $model = self::where($conditions)->first();
        return ($model ? $model->address_type : null);
    }
    static public function isOrgAddressTypePrimary($type) {
        return self::getOrgAddressTypePrimary() == $type;
    }

    static public function getOrgAddressTypes() {
        $conditions = ['is_org_address' => 'Y'];
        return self::where($conditions)->get();
    }

    static public function isValidOrgAddressType($type) {
        if (!$type) return false;
        $conditions = [
            'address_type' => $type,
            'is_org_address' => 'Y'
        ];
        return self::where($conditions)->exists();
    }

    static public  function getOrgAddressTypeByType($type) {
        $conditions = [
            'address_type' => $type,
            'is_org_address' => 'Y'
        ];
        return self::where($conditions)->first();
    }

    static public function getAll() {
        return self::all();
    }


}