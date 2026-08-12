<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\HGA;

use App\Models\FundStatement;
use App\Helpers\GnUtils;
use Auth;
use Illuminate\Http\Request;


class HGAStatement
{

    /**
     * API get statement
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function apiFundStatement($id, Request $request)
    {
        $groups = [];
        $statement = [];
        $fund = FundStatement::getById($id);
        if (!$fund) abort('404', 'Fund statement not found');

        // fund
        $statement['fund_name'] = isset($fund['fund_name']) ? $fund['fund_name'] : '';
        $statement['account_id'] = isset($fund['account_number']) ? $fund['account_number'] : '';

        // contact
        $statement['contact_name'] = isset($fund['admin_name']) ? $fund['admin_name'] : '';
        $statement['contact_phone'] = isset($fund['admin_phone']) ? $fund['admin_phone'] : '';
        $statement['contact_email'] = isset($fund['admin_email']) ? $fund['admin_email'] : '';

        $statement['from_date'] = isset($fund['from_date']) ? $fund['from_date'] : '';
        $statement['thru_date'] = isset($fund['thru_date']) ? $fund['thru_date'] : '';

        $groups['info'] = $statement;
        // holding summary
        $groups['holding_summary'] = $this->getHoldingSummary($fund);

        // activity summary
        $groups['activity_summary'] = $this->getActivitySummary($fund);

        // holding details
        $groups['holdings'] = $this->getHoldingDetails($fund);

        // contribution details
        $groups['contributions'] = $this->getContributionDetails($fund);

        // distribution details
        $groups['distributions'] = $this->getDistributionDetails($fund);

        // net investment income details
        $groups['income'] = $this->getIncomeDetails($fund);

        // return ['data' => compact('statement', 'fund')];
        return ['data' => compact('groups', 'fund')];
    }

    private function getIncomeDetails($fund)
    {
        $summary = [];

        $row['name'] = "INTEREST INCOME";
        $row['amount'] = isset($fund["int"]) ? GnUtils::money($fund["int"]) : '-';
        $summary[] = $row;

        $row['name'] = "DIVIDEND INCOME";
        $row['amount'] = isset($fund["div"]) ? GnUtils::money($fund["div"]) : '-';
        $summary[] = $row;

        $row['name'] = "CAPITAL GAIN DISTRIBUTION";
        $row['amount'] = isset($fund["capgndist"]) ? GnUtils::money($fund["capgndist"]) : '-';
        $summary[] = $row;

        $row['name'] = "REALIZED GAIN/LOSS";
        $row['amount'] = isset($fund["realizedgainloss"]) ? GnUtils::money($fund["realizedgainloss"]) : '-';
        $summary[] = $row;

        $row['name'] = "ADMINISTRATIVE FEES";
        $row['amount'] = isset($fund["adminfees"]) ? GnUtils::money($fund["adminfees"]) : '-';
        $summary[] = $row;

        $row['name'] = "EXPENSES";
        $row['amount'] = isset($fund["expenses"]) ? GnUtils::money($fund["expenses"]) : '-';
        $summary[] = $row;

        $data['rows'] = $summary;

        $data['total_name'] = "GRANT TOTAL";
        $data['total_amount'] = isset($fund["netincometotal"]) ? GnUtils::money($fund["netincometotal"]) : '-';
        return $data;
    }

    private function getDistributionDetails($fund)
    {
        $summary = [];
        for($i=1; $i < 10; ++$i) {
            if (isset($fund["fund_grants_{$i}_grant_date"]) && $fund["fund_grants_{$i}_grant_date"] != "") {
                $row = [];
                $row["date"] = $fund["fund_grants_{$i}_grant_date"];
                $row["grantee"] = isset($fund["fund_grants_{$i}_grantee"]) ? $fund["fund_grants_{$i}_grantee"] : '-';
                $row["description"] = isset($fund["fund_grants_{$i}_description"]) ? $fund["fund_grants_{$i}_description"] : '-';
                $row["amount"] = isset($fund["fund_grants_{$i}_amount"]) ? GnUtils::money($fund["fund_grants_{$i}_amount"]) : '-';
                $summary[] = $row;
            }
        }
        $data['rows'] = $summary;
        $data['total'] = isset($fund['fund_grants_total']) ? GnUtils::money($fund['fund_grants_total']) : '-';
        return $data;
    }

    private function getContributionDetails($fund)
    {
        $summary = [];
        for($i=1; $i < 10; ++$i) {
            if (isset($fund["fund_gifts_{$i}_gift_date"]) && $fund["fund_gifts_{$i}_gift_date"] != "") {
                $data = [];
                $data["date"] = $fund["fund_gifts_{$i}_gift_date"];
                $data["description"] = isset($fund["fund_gifts_{$i}_description"]) ? $fund["fund_gifts_{$i}_description"] : '-';
                $data["amount"] = isset($fund["fund_gifts_{$i}_amount"]) ? GnUtils::money($fund["fund_gifts_{$i}_amount"]) : '-';
                $summary[] = $data;
            }
        }
        $data['rows'] = $summary;
        $data['total'] = isset($fund['fund_gifts_total']) ? GnUtils::money($fund['fund_gifts_total']) : '-';
        return $data;
    }

    private function getHoldingDetails($fund)
    {
        $summary = [];
        $summary[] = [
            "name" => 'CASHYTD',
            "share" => "0.00",
            "price" => "",
            "value" => ""
        ];

        for($i=1; $i < 10; ++$i) {
            if (isset($fund["fund_{$i}_lnm"]) && $fund["fund_grants_{$i}_grant_date"] != "") {
                $data = [];
                $data["name"] = $fund["fund_{$i}_lnm"];
                $data["share"] = isset($fund["fund_{$i}_shares"]) ? GnUtils::money($fund["fund_{$i}_shares"], '') : '-';
                $data["price"] = isset($fund["fund_{$i}_price"]) ? GnUtils::money($fund["fund_{$i}_price"]) : '-';
                $data["value"] = isset($fund["fund_{$i}_end_mv"]) ? GnUtils::money($fund["fund_{$i}_end_mv"]) : '-';
                $summary[] = $data;
            }
        }
        $data['rows'] = $summary;
        $data['total'] = isset($fund["total_end_mv"]) ? GnUtils::money($fund["total_end_mv"]) : '-';
        return $data;
    }

    private function getActivitySummary($fund)
    {
        $summary = [];

        $row['name'] = "BEGINNING BALANCE";
        $row['amount'] = isset($fund["total_begin_mv"]) ? GnUtils::money($fund["total_begin_mv"]) : '-';
        $summary[] = $row;

        $row['name'] = "GRANT DISTRIBUTIONS";
        $row['amount'] = isset($fund["total_grant_disbursements"]) ? GnUtils::money($fund["total_grant_disbursements"]) : '-';
        $summary[] = $row;

        $row['name'] = "NET INTEREST INCOME";
        $row['amount'] = isset($fund["int"]) ? GnUtils::money($fund["int"]) : '-';
        $summary[] = $row;

        $row['name'] = "HIGHGROUND INVESTMENT FUND INCOME";
        $row['amount'] = isset($fund["div"]) ? GnUtils::money($fund["div"]) : '-';
        $summary[] = $row;

        $row['name'] = "GAIN DISTRIBUTIONS";
        $row['amount'] = isset($fund["capgndist"]) ? GnUtils::money($fund["capgndist"]) : '-';
        $summary[] = $row;

        $row['name'] = "REALIZED GAIN LOSS";
        $row['amount'] = isset($fund["realizedgainloss"]) ? GnUtils::money($fund["realizedgainloss"]) : '-';
        $summary[] = $row;

        $row['name'] = "CHANGE IN MARKET VALUE";
        $row['amount'] = isset($fund["unrealizedgainloss"]) ? GnUtils::money($fund["unrealizedgainloss"]) : '-';
        $summary[] = $row;

        $data['rows'] = $summary;

        $data['total_name'] = "ENDING BALANCE";
        $data['total_amount'] = isset($fund["total_end_mv"]) ? GnUtils::money($fund["total_end_mv"]) : '-';

        return $data;
    }

    private function getHoldingSummary($fund)
    {
        $total_percent = 0;
        $summary = [];
        for($i=1; $i < 10; ++$i) {
            if (isset($fund["fund_{$i}_lnm"]) && $fund["fund_{$i}_lnm"] != "") {
                $data = [];
                $data["name"] = $fund["fund_{$i}_lnm"];
                $data["begin"] = isset($fund["fund_{$i}_begin_mv"]) ? GnUtils::money($fund["fund_{$i}_begin_mv"]) : '-';
                $data["end"] = isset($fund["fund_{$i}_end_mv"]) ? GnUtils::money($fund["fund_{$i}_end_mv"]) : '-';
                $data["percent"] = isset($fund["fund_{$i}_pct"]) ? GnUtils::percent($fund["fund_{$i}_pct"]) : '-';
                $total_percent += isset($data["percent"]) ? $data["percent"] : 0;
                $summary['rows'][] = $data;
            }
        }
        $summary['total_begin'] = isset($fund["total_begin_mv"]) ? GnUtils::money($fund["total_begin_mv"]) : '-';
        $summary['total_end'] = isset($fund["total_end_mv"]) ? GnUtils::money($fund["total_end_mv"]) : '-';
        $summary['total_percent'] = GnUtils::percent($total_percent);

        return $summary;
    }

}
