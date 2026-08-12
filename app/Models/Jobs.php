<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/13/2021
 * Time: 9:46 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{

    static public function getPaginated()
    {
        return self::paginate();
    }

    static public function get($count)
    {
        return self::limit($count)->get();
    }

}