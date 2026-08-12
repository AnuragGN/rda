<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suffix extends Model
{
    /* @var string */
    protected $table = 'suffix';

    protected $primaryKey = null;
    public $incrementing = false;

    static public function getSelectable($addNull=true) {
        $suffixes = self::all()->sortBy('suffix')->pluck('suffix');
        $data = [];
        if ($addNull) $data[''] = '';
        foreach($suffixes as $suffix) {
            $data[$suffix] = $suffix;
        }
        return $data;
    }

}