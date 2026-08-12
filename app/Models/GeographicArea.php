<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 21-09-2020
 * Time: 11:33
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 * interest_area_id: "A110",
 * description: null,
 * interest_area: "Health Care",
 * parent_interest_area_id: "A100"
 */
class GeographicArea extends Model
{
    /* @var string */
    protected $table = 'geographic_area';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function getAll()
    {
        return GeographicArea::where([])->orderBy('order_of', 'asc')->get();
    }

}