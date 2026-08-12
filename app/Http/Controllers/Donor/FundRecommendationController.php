<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/12/2023
 * Time: 4:17 PM
 */

namespace App\Http\Controllers\Donor;

use App\Helpers\GnUtils;
use App\Http\Controllers\Controller;
use App\Models\Fund;
use App\Models\FundRecommendation;
use App\Models\GrantItem;
use Illuminate\Http\Request;

class FundRecommendationController extends Controller
{

    // Recurring Grants
    public function recurringGrants(Request $request, $id=null)
    {
        GnUtils::addBreadcrumb('Recurring Grants');

        $models = FundRecommendation::getRecurringGrants($id);

        $funds = Fund::getSelectableForGrantRecommendation();
        if (!count($funds)) {
            return redirect()->back()->with('danger', 'There is no fund information.');
        }
        $funds = array_merge(['all' => 'All'], $funds);
        $selectedFund = $id;

        $total = 0;
        foreach($models as $model) {
            $total += $model->amount;
        }

        return view('donor.grants.recurring-grants', compact('models', 'total', 'funds', 'selectedFund'));
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $model = FundRecommendation::findOrFail($id);
        if ($model->recurring_status == GrantItem::RECUR_STATUS_COMPLETED) {
            return redirect()->back()->with('danger', 'This recommendation is already completed');
        }

        $message = "You have canceled this recurring grant. The grants team has been notified of the cancellation and are processing the request";
        if ($model->recurring_status == GrantItem::RECUR_STATUS_ACTIVE) {
            $model->recurring_status = GrantItem::RECUR_STATUS_CANCELED;
            $model->save();
            return redirect()->back()->with('danger', $message);
        }
        if ($model->recurring_status == GrantItem::RECUR_STATUS_CANCELED) {
            return redirect()->back()->with('danger', $message);
        }
        return redirect()->back();
    }

}