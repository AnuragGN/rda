<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 05-08-2020
 * Time: 19:21
 */

namespace App\Models;

/*
 * contact_id
 * contact_type_id
 */

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactTypeContact - Contacts and ContactTypes
 * @package App
 */
class ContactTypeContact extends Model
{
    /* @var string */
    protected $table = 'contact_type_contact';

    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = null;

}