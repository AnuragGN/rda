<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 5/20/2021
 * Time: 10:59 AM
 */

namespace App\Http\Controllers\Donor;

use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\ClientInfo;
use App\Models\ContactFund;
use App\Models\Fund;
use App\Models\GhSegment;
use App\Models\PerformanceData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerformanceController extends Controller
{

    /**
     * fund performance
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fundPerformance($id, Request $request)
    {
        GnUtils::addBreadcrumb('Fund', route('fund', $id));
        GnUtils::addBreadcrumb('Fund Performance');

        $fund = Fund::getFundById($id);
        if (!$fund) abort(404);

        if (ClientInfo::isGNA()) {
            // Gifting Network
            $accountId = 'Beth';
            $accountType = 'fund';
            return view('donor.performance.fund-performance-page', compact('accountId', 'accountType', 'fund'));
        }

        // Make sure that the session user can view the performance
        ContactFund::assertViewable($id);

        // JCF & Gifting Network
        $accountId = $id;
        $accountType = 'fund';
        return view('donor.performance.fund-performance-page', compact('accountId', 'accountType', 'fund'));
    }

    /**
     * general performance
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function poolPerformance(Request $request)
    {
        GnUtils::addBreadcrumb('Pool Performance');

        // JCF & Gifting Network
        $segmentId = $request->id;
        $segment = GhSegment::getBySegmentId($segmentId);
        if (!$segment) {
            $segmentId = 4001;
            $segment = GhSegment::getBySegmentId($segmentId);
            if (!$segment) abort(404);
        }
        $accountId = $segmentId + 10000;
        $accountType = 'pool';
        return view('donor.performance.pool-performance-page', compact('accountId', 'accountType', 'segment'));
    }

    /**
     * common for DONOR & AGENCY
     *
     * @param Request $request
     * @return mixed
     */
    public function fundPerformanceDownload(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        // Make sure that the session user can view the performance
        if ($type !== 'pool') ContactFund::assertViewable($id);

        $headers = [];
        $name = $id . '_performance.pdf' ;
        $name = preg_replace('/\s+/', '_', $name);
        $file = PerformanceData::performanceFileName($id);
        if (!$file) abort(404);
        return Storage::download($file, $name, $headers);
    }

	// for testing
    public function ajaxPoolPerformance(Request $request)
    {
        set_time_limit(180);
        $accountId = $request->account_id;
        $accountType = $request->account_type;

        if ($request->account_id == null || $request->account_type == null) {

        }
        return view('agency.performance.returns-summary', compact('accountId', 'accountType'));
    }

}
