<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\Http\Controllers\Donor;

use App\CCT\CCTStatement;
use App\FFP\FFPStatement;
use App\Http\Controllers\Controller;
use App\JSV\JSVStatement;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\ContactFund;
use App\GMF\GMFStatement;
use App\GNA\GNAStatement;
use App\Helpers\GnUtils;
use App\HGA\HGAStatement;
use App\JCF\JCFStatement;
use App\Models\Fund;
use App\Models\GhComposition;
use App\Models\LogActivity;
use App\Mercy\MercyStatement;
use App\NIF\NIFStatement;
use App\NTC\NTCStatement;
use Auth;
use Illuminate\Http\Request;
use PDF;

// groups types  - 'group', 'info', 'balance'
// item types    - 'single', 'pool',
// item subtypes - (for pool) 'pool-default', 'pool-indented'
//               - (for single) 'default', 'named-link', 'self-link', 'fund-linked'


class FundStatementController extends Controller
{

    /**
     * fund statement
     *
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fundStatement(Request $request, $id = null)
    {
        GnUtils::addBreadcrumb('Fund Statement');

        // Make sure that the session user can view the statement
        ContactFund::assertViewable($id);

        $api = $this->apiFundStatement($id, $request);
        if (!$api || !isset($api['data'])) {
            return view(ClientInfo::clientViewFor('statements.not-found', 'donor.'), ['date' => $request->date ? true : false]);
        }

        $data = $api['data'];

        // name could be different in fund and fund statement, use Fund name (#3782)
        $fundName = Fund::getNameById($id);
        if ($fundName && isset($data['fund'])) {
            $data['fund']->fund_name = Fund::getNameById($id);
        }

        // log activity
        $activity = new LogActivity(LogActivity::NAME_FUND, LogActivity::ACTION_VIEW);
        $activity->onModel(isset($data['fund']) ? $data['fund'] : null)->add();

        if ($request->print) {
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadView(ClientInfo::clientViewFor('statements.pdf'), [
                'fund' => $data['fund'],
                'groups' => $data['groups'],
                'print' => $request->print
            ]);
            return $pdf->stream();
        }

        $fundComposition = null;
        if (ClientInfo::isJCF()) {
            $data['ghPools'] = [];
            if (GnUtils::isDonorSession()) {
                // if available, show fund composition. Don't show pool composition.
                $fundComposition = GhComposition::getFundCompositionData($id, false);
            }
        } else if (ClientInfo::isGNA()) {
            // if available, show fund composition. Else, show pool composition.
            $fundComposition = GhComposition::getFundCompositionData($id, false);
            if ($fundComposition || !isset($data['ghPools'])) {
                $data['ghPools'] = [];
            }
        } else {
            // Don't show any of them
            $fundComposition = null;
            $data['ghPools'] = [];
        }

        return view(ClientInfo::clientViewFor('statements.overview'), [
                'fund' => $data['fund'],
                'groups' => $data['groups'],
                'ghPools' => $data['ghPools'],
                'fundComposition' => $fundComposition,
                'date' => $request->date
            ]
        );
    }

    /**
     * API get statement
     * $request->date format is "mm-dd-yyyy" for JCF ThruDate
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function apiFundStatement($id, Request $request)
    {
        $service = null;
        if (ClientInfo::isGMF()) {
            $service = new GMFStatement();
        } else if (ClientInfo::isJCF()) {
            $service = new JCFStatement();
        } else if (ClientInfo::isHGA()) {
            $service = new HGAStatement();
        } else if (ClientInfo::isGNA()) {
            $service = new GNAStatement();
        } else if (ClientInfo::isNIF()) {
            $service = new NIFStatement();
        } else if (ClientInfo::isJSV()) {
            $service = new JSVStatement();
        } else if (ClientInfo::isMercy()) {
            $service = new MercyStatement();
        } else if (ClientInfo::isCCT()) {
            $service = new CCTStatement();
        } else if (ClientInfo::isNTC()) {
            $service = new NTCStatement();
        } else if (ClientInfo::isFFP()) {
            $service = new FFPStatement();
        } else {
            return null;
        }
        return $service->apiFundStatement($id, $request);
    }

}
