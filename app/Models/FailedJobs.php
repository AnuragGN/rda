<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/13/2021
 * Time: 9:50 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FailedJobs extends Model
{

    static public function getPaginated()
    {
        return self::paginate();
    }

}