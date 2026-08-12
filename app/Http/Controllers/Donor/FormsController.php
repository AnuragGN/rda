<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 13-05-2020
 * Time: 15:55
 */

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Helpers\GnUtils;


class FormsController extends Controller
{
    public function index ()
    {
        return view('donor.forms.index');
    }

}