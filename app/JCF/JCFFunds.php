<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 03-07-2020
 * Time: 19:23
 */

namespace App\JCF;


use App\Models\Fund;
use App\Models\FundStatement;
use App\Models\FundStatementHeldAway;
use App\Models\FundStatementOne;

class JCFFunds
{

    /**
     * Full fund statement including table one and heal-away
     *
     * @param String $ThruDate
     * @param $fid
     * @return mixed
     */
    static public function getFundStatementFull($fid, $thruDate=null)
    {
        // main statement
        $statement =  FundStatement::getByIdAndThruDate($fid, $thruDate);
        if (!$statement) return null;

        // read data from extension - table one
        if ($statement->fund_statement_id){
            $fundExtension = FundStatementOne::getByStatementId($statement->fund_statement_id);
            if ($fundExtension) {
                $items = $fundExtension->toArray();
                foreach ($items as $key => $value) {
                    $statement->$key = $value;
                }
            }
        }

        // read data from held-away table
        $fundHA = FundStatementHeldAway::getByFundIdAndThruDate($fid, $thruDate);
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
            $cfs[] = FundStatementHeldAway::where('fund_id', 'ilike', $childFund->fund_id)
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
        $balance = $fund->statement_balance;
        $heldAwayChildrenTotal = self::getHeldAwayChildrenTotal($fund);
        $heldAwayPoolsTotal = self::getHeldAwayAssetPoolsTotal($fund);

        // get cash value
        $statement = FundStatement::getById($fund->fund_id);
        if ($statement && $statement['cashytd'] != 0) {
            $balance += $statement['cashytd'];
        }

        return $balance + $heldAwayChildrenTotal + $heldAwayPoolsTotal;
    }

    /**
     * @param Fund $fund
     * @return int
     */
    static private function getHeldAwayChildrenTotal(Fund $fund)
    {
        $records = JCFFunds::getHeldAwayImpactFundsByFundId($fund->fund_id);
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
        // $statement =  self::getFundStatementFull($fund->fund_id);
        $statement = FundStatementHeldAway::getByFundIdAndThruDate($fund->fund_id, null);
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