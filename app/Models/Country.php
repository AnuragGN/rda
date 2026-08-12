<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /* @var string */
    protected $table = 'country';

    protected $primaryKey = null;
    public $incrementing = false;

    static public function getList() {
        $countries = self::all()->pluck('country');
        $data = [];
        foreach($countries as $country) {
            $data[$country] = $country;
        }
        return $data;
    }

    static public function getListUSAOnly()
    {
        return ['USA' => 'USA'];
    }

    static public function getListDAF() {
        if (ClientInfo::isHGA()) {
            $countries = self::all()->pluck('country');
            $data = [];
            foreach ($countries as $country) {
                $data[$country] = $country;
            }
            return $data;
        } else {
            return ['USA' => 'USA'];
        }
    }

}