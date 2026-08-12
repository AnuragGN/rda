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
class PopulationServed extends Model
{
    /* @var string */
    protected $table = 'population_served';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    static public function getAll()
    {
        return PopulationServed::where([])->orderBy('order_of', 'asc')->get();
    }

}