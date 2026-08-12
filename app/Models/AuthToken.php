<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-07-2020
 * Time: 12:25
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    /* @var string */
    protected $table = 'auth_token';

    /* @var boolean */
    public $timestamps = false;

}
