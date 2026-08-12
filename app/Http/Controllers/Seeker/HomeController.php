<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/15/2021
 * Time: 8:34 PM
 */

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class HomeController  extends Controller
{

    public function home(Request $request)
    {
        $org = Organization::find(1);
        return view('seeker.home.dashboard', compact('org'));
    }


}