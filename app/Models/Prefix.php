<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    /* @var string */
    protected $table = 'prefix';

    protected $primaryKey = null;
    public $incrementing = false;

    static public function getSelectable($addNull=true) {
        $prefixes = self::all()->sortBy('prefix')->pluck('prefix');
        $data = [];
        if ($addNull) $data[''] = '';
        foreach($prefixes as $prefix) {
            $data[$prefix] = $prefix;
        }
        return $data;
    }

}