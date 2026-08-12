<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/14/2023
 * Time: 12:49 PM
 */

namespace App\FFP;

use App\Models\Fund;
use App\Models\FundStatement;
use App\Models\FundStatementHeldAway;

class FFPFunds
{

    /**
     * Full fund statement including table one and heal-away
     *
     * @param $fid
     * @return mixed
     */
    static public function getFundStatementFull($fid)
    {
        // main statement
        $statement =  FundStatement::getById($fid);
        if (!$statement) return null;

        // read data from held-away table
        $fundHA= FundStatementHeldAway::getByFundId($fid);
        if ($fundHA) {
            $items = $fundHA->toArray();
            foreach ($items as $key => $value) {
                $statement->$key = $value;
            }
        }

        return $statement;
    }

    static public function getHeldAwayImpactFundsByFundId($fundId)
    {
        // get child funds
        $childFunds = Fund::where('fund_parent_id', 'ilike', $fundId)->get();

        // get child fund held away statements
        $cfs = [];
        foreach($childFunds as $childFund) {
            $cfs[] = \App\Models\FundStatementHeldAway::where('fund_id', 'ilike', $childFund->fund_id)
                ->orderBy('created_date', 'DESC')->first();
        }

        $items = [];
        foreach($cfs as $cf) {
            $item['tick'] = $cf['openfintick1'];
            $item['mv'] = $cf['openfin1mv'];
            $item['desc'] = $cf['openfin1desc'];
            $item['date'] = $cf['openfin1pdate'];
            $item['cust'] = $cf['openfin1cust'];
            $items[] = $item;
        }

        return $items;
    }


    /**
     * Fund balance
     * @param Fund $fund
     * @return mixed
     */
    static public function getStatementBalance(Fund $fund)
    {
        $balance = $fund->balance;
        // $heldAwayChildrenTotal = self::getHeldAwayChildrenTotal($fund);
        $heldAwayPoolsTotal = self::getHeldAwayAssetPoolsTotal($fund);
        return $balance + $heldAwayPoolsTotal;
    }

    /**
     * @param Fund $fund
     * @return int
     */
    static private function getHeldAwayChildrenTotal(Fund $fund)
    {
        $records = FFPFunds::getHeldAwayImpactFundsByFundId($fund->fund_id);
        if (!count($records)) return 0;
        $total = 0;
        foreach ($records as $i => $record) {
            $total += $record['mv'];
        }
        return $total;
    }

    /**
     * @param $fund
     * @return int
     */
    static public function getHeldAwayAssetPoolsTotal(Fund $fund)
    {
        $total = 0;
        $statement =  self::getFundStatementFull($fund->fund_id);
        if ($statement) {
            for ($i = 1; $i <= 100; ++$i) {
                $key = 'openfin' . $i . 'mv';
                if ($statement[$key]) {
                    $total += $statement[$key];
                }
            }
        }
        return $total;
    }

}