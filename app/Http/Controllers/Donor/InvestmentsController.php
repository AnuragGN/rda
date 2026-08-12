<?php

namespace App\Http\Controllers\Donor;

use App\Forms\FormChangeEmail;
use App\Forms\FormInvestments;
use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\ClientConfig;
use App\Models\ClientInfo;
use App\Models\Contact;
use App\Models\Fund;
use App\Models\InvestmentPerformance;
use App\Models\Investments;
use App\Models\FundPool;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvestmentsController extends Controller
{

    /**
     * @param null $id FundId
     * @return mixed
     */
    public function getInvestments($id=null)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('INVESTMENTS'));

        $selector = Fund::getSelectableViewable();
        if (count($selector) < 1) abort(404);
        if (!$id) {
            $keys = array_keys($selector);
            return redirect(route('get-investments', $keys[0]));
        }

        // get allocations
        $allocations = Investments::getCurrentAllocationData($id);

        $requested = false;
        foreach($allocations as $allocation){
            if ($allocation->action == Investments::ACTION_REQUEST){
                $requested = true;
            }
        }

        return view('donor.investments.show', compact('id', 'selector', 'requested', 'allocations'));
    }

    /**
     * @param null $id FundId
     * @return mixed
     */
    public function editInvestments($id=null)
    {
        GnUtils::addBreadcrumb(ClientConfig::text('INVESTMENTS'), route('get-investments'));
        GnUtils::addBreadcrumb('Change');

        $selector = Fund::getSelectableViewable();
        if (count($selector) < 1) abort(404);
        if (!$id) {
            $keys = array_keys($selector);
            return redirect(route('get-investments', $keys[0]));
        }

        $model = new FormInvestments();
        $allocations = Investments::getCurrentAllocationData($id);
        return view('donor.investments.edit', compact('id', 'model', 'selector', 'allocations'));
    }

    /**
     * @param Request $request
     * @return $this
     * @throws ValidationException
     */
    public function saveInvestments(Request $request)
    {
        // emulation mode
        if (GnUtils::isEmulationMode()) {
            return redirect()->back()->with('success', GConst::M_EMULATION_MODE);
        }

        $fundId = $request->fund_id;

        $allocations = $request->allocations;

        // all pool Ids must be valid
        $keys = array_keys($allocations);
        $count = FundPool::whereIn('pool_id', $keys)->count();
        if (count($keys) < 1 || count($keys) !== $count) {
            throw ValidationException::withMessages(['allocation' => 'One or more Pool Ids are not valid']);
        }

        // sum of allocations must be 100
        if (array_sum($allocations) !== 100) {
            throw ValidationException::withMessages(['allocation' => 'Sum of allocations must be 100']);
        }
        $fundAllocations = Investments::where([
            'fund_id' => $fundId,
            'status' => Investments::STATUS_ACTIVE
        ])->pluck('requested_allocation', 'pool_id')->toArray();

        foreach($allocations as $poolId => $value) {
            $model = Investments::where([
                'fund_id' => $fundId,
                'pool_id' => $poolId,
                'status' => Investments::STATUS_ACTIVE
            ])->first();

            if ($model) {
                $model->requested_allocation = $value;
                $model->action = Investments::ACTION_REQUEST;
                $model->status = Investments::STATUS_ACTIVE;
                $model->save();
            } else {
                $model = new Investments();
                $model->pool_id = $poolId;
                $model->fund_id = $fundId;
                $model->allocation = 0;
                $model->requested_allocation = $value;
                $model->action = Investments::ACTION_REQUEST;
                $model->status = Investments::STATUS_ACTIVE;
                $model->save();
            }
        }

        $updates = array_diff_assoc($allocations, $fundAllocations);
        if ( $updates != null ) {
            $allocations = Investments::getCurrentAllocationData($fundId);
            Email::allocationUpdated($allocations);
        }

        return redirect(route('get-investments', $fundId))->with('success', 'Information has been saved');
    }

	// HGA
    public function investmentFundPerformance()
    {
        GnUtils::addBreadcrumb('Fund Performance');

        $funds = InvestmentPerformance::orderBy('order_seq', 'asc')->get();

        return view('donor.investments.investment-fund-performance', compact('funds'));
    }

	// HGA
    public function researchInvestmentOptions()
    {
        GnUtils::addBreadcrumb('Research Investment Options');

        return view('donor.investments.research-investment-options');
    }
}
