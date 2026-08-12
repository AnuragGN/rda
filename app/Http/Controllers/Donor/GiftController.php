<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 13-05-2020
 * Time: 15:55
 */

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Forms\FormPayTo;
use App\Models\Fund;
use App\Models\GrantHistory;
use App\Helpers\GnUtils;
use App\Models\LogActivity;
use App\Models\Organization;
use Illuminate\Http\Request;

class GiftController extends Controller
{

    /**
     * Deleted - Not In Use!
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        // log activity - create a new grant
        $activity = new LogActivity(LogActivity::NAME_GIFT, LogActivity::ACTION_CREATE);
        $activity->data($request->all())->add();

        GnUtils::addBreadcrumb('Make a Gift');

        return view('gifts.make-a-gift', compact('model', 'funds', 'organization'));
    }
}