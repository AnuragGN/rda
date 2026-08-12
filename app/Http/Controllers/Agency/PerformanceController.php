<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/20/2021
 * Time: 10:59 AM
 */

namespace App\Http\Controllers\Agency;

use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\ContactFund;
use App\Models\Fund;
use App\Models\GhSegment;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{

    // fund performance
    public function fundPerformance($id, Request $request)
    {
        GnUtils::addBreadcrumb('Fund', route('fund', $id));
        GnUtils::addBreadcrumb('Fund Performance');

        $fund = Fund::getFundById($id);
        if (!$fund) abort(404);

        // Make sure that the session user can view the fund performance
        ContactFund::assertViewable($id);

        $accountId = $id;
        $composite = false;
        if (GnUtils::isAgencySession() && $request->composite) {
            $composite = true;
            $accountId = $id . "_comp";
        }
        $accountType = 'fund';
        return view('agency.performance.fund-performance-page', compact('accountId', 'accountType', 'fund', 'composite'));
    }

    // general performance
    public function poolPerformance(Request $request)
    {
        GnUtils::addBreadcrumb('Pool Performance');

        $segmentId = $request->id;
        $segment = GhSegment::getBySegmentId($segmentId);
        if (!$segment) {
            $segmentId = 4001;
            $segment = GhSegment::getBySegmentId($segmentId);
            if (!$segment) abort(404);
        }
        $accountId = $segmentId + 10000;
        $accountType = 'pool';
        return view('agency.performance.pool-performance-page', compact('accountId', 'accountType', 'segment'));
    }

}