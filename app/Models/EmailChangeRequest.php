<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChangeRequest extends Model
{
    /* @var string */
    protected $table = 'email_change_request';

    public $incrementing = false;

    const ECR_RECEIVED = 0;
    const ECR_COMPLETED = 1;

}