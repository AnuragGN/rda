<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /* @var string */
    protected $table = 'state';

    protected $primaryKey = null;
    public $incrementing = false;

    static public function getCodeListUSA() {
        $states = self::where([])->orderBy('state', 'asc')->pluck('state', 'state_code');
        return $states;
    }

    /**
     * @return null
     */
    static public function getDefaultStateCode()
    {
        $defaultState = self::where('selected', 'Y')->first();
        return $defaultState ? $defaultState->state_code : null;
    }
}