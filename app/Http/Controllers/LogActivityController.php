<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Auth;
use Illuminate\Http\Request;

// Funds = 'JCFEX', 'Abra';
/**
 * Class FundController
 * @package App\Http\Controllers
 */
class LogActivityController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $user = null;
        $activity = new LogActivity(LogActivity::NAME_AUTH, LogActivity::ACTION_LOGIN);
        $activity->data(['username' => 'alkesh']);
        if ($user) $activity->onModel($user);
        $activity->description(LogActivity::DESCRIPTION_SUCCESS)->add();
        return $activity;
    }


    public function getData()
    {
        $logs = LogActivity::activities();
        return $logs;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function activities()
    {
        $logs = LogActivity::activities();
        return view('log-activity',compact('logs'));
    }

}