<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\CCT;

use App\Helpers\GnUtils;
use App\Models\FundStatement;
use Auth;
use App\Models\Api;
use Illuminate\Http\Request;

// groups types  - 'group', 'info', 'balance'
// item types    - 'single', 'pool',
// item subtypes - (for pool) 'pool-default', 'pool-indented'
//               - (for single) 'default', 'named-link', 'self-link', 'fund-linked'


class CCTStatement
{
    public function apiFundStatement($id, Request $request)
    {
        $fund = FundStatement::getById($id);
        if (!$fund) return null;

        $fund->ending_balance = GnUtils::StrToMoney($fund->ending_balance);
        $fund->end_total_bal = GnUtils::StrToMoney($fund->end_total_bal);

        $groups = [];
        for($i=1; $i<10; ++$i) {
            $item = [];

            $unitVar = 'units_' . $i;
            if (!isset($fund->$unitVar)) continue;

            $units = intval($fund->$unitVar);
            if (is_nan($units) || $units < 1) continue;

            $item['units'] = $units;

            $priceVar = 'price_' . $i;
            $item['price'] = GnUtils::StrToMoney($fund->$priceVar);

            $tickerVar = 'ticker_' . $i . '_name';
            $item['ticker'] = $fund->$tickerVar;

            $productVar = 'product_' . $i . '_name';
            $item['product'] = $fund->$productVar;

            $tickerVar = 'ticker_' . $i . '_total';
            $item['total'] = GnUtils::StrToMoney($fund->$tickerVar);

            $assetVar = 'asset_' . $i . '_category';
            $item['asset'] = $fund->$assetVar;

            $groups[] = $item;
        }

        return [
            'data' => [
                'fund' => $fund,
                'groups' => $groups
            ]
        ];

    }

}
