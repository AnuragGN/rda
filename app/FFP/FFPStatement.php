<?php

/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 4/14/2023
 * Time: 12:49 PM
 */

namespace App\FFP;

use App\Helpers\GnUtils;
use Auth;
use App\Models\Api;
use Illuminate\Http\Request;

// groups types  - 'group', 'balance'
// item types    - 'single', 'pool',
// item subtypes - (for pool) 'pool-default', 'pool-indented'
//               - (for single) 'default', 'named-link', 'self-link', 'fund-linked'

class StatementItem {

    // fund_74_totalassetsytd
    // fund_94_totalliabilitiesandfund
    // fund_100_beginningfundbalanceytd - fund_100_beginningfundbalanceyt
    static $assetMap = [
        'order' => 1,
        'type' => 'single',
        'subtype' => 'default',
        'name' => 'Total Invested Assets',
        'key' => 'fund_74_totalassetsytd',
        'show' => 'always'
    ];

    static $poolsMap = [
        [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Short Term Pool',
            'key' => 'fund_30_shorttermpoolytd',
            'children' => null,
            // 'childrenHint' => 'shorttermpool',
            'childrenHint' => 'shortterminv',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Mid Term Pool',
            'key' => 'fund_40_midtermpoolytd',
            'children' => null,
            'childrenHint' => 'midtermpool',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Long Term Pool',
            'key' => 'fund_42_longtermpoolytd',
            'children' => null,
            'childrenHint' => 'longtermpool',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'High Equity Pool',
            'key' => 'fund_48_highequitypoolytd',
            'children' => null,
            'childrenHint' => 'highequitypool',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Impact Investment Pool',
            'key' => 'fund_50_impactinvestmntpool',
            'children' => null,
            'childrenHint' => 'impactinvestmntpool',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Endowment Pool',
            'key' => 'fund_44_endowmentpoolytd',
            'children' => null,
            // 'childrenHint' => 'endowmentpool',
            'childrenHint' => 'endpoolinv',
            'max' => 150,
            'show' => 'conditional'
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Long Term Index Pool',
            'key' => 'fund_46_ltippoolytd',
            'children' => null,
            'childrenHint' => 'ltippool',
            'max' => 150,
            'show' => 'conditional'
        ]

    ];

    // fund_250_totalinvestactivityytd: "0.53",
    // fund_390_netchangeytd: null,
    // fund_225_unrealizedgainslosses: "0.53",

    // activity beginning asset balance
    static $abaBalanceMap = [
        'order' => 1,
        'type' => 'single',
        'subtype' => 'default',
        'name' => 'Beginning Assets Balance',
        // 'key' => 'fund_100_beginningfundbalanceytd',
        'key' => 'fund_100_beginningfundbalanceyt',
        'show' => 'always'
    ];

    // order 1 has been moved out of map
    static $activitiesMap = [
        [
            'order' => 2,
            'type' => 'single',
            'subtype' => 'named-link',
            'name' => 'Contributions',
            'key' => 'fund_127_total_contributions',
            'link' => 'gift-history',
            'linkTitle' => 'Show Gift History',
            'show' => 'always'
        ],
        [
            'order' => 3,
            'type' => 'single',
            'subtype' => 'named-link',
            'name' => 'Disbursements',
            'key' => 'fund_262_grantsdisbursementytd', // fund_269_total_disbursements',
            'link' => 'grant-history',
            'linkTitle' => 'Show Grant History',
            'show' => 'always'
        ],
        [
            'order' => 4,
            'type' => 'pool',
            'subtype' => 'pool-default',
            'name' => 'Investment Activity',
            'key' => 'fund_250_totalinvestactivityytd',
            'children' => [
                [
                    'order' => 1,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Interest & Dividends',
                    'key' => 'fund_200_interestanddividendsytd',
                    'show' => 'conditional'
                ],[
                    'order' => 2,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Realized Gains/Losses',
                    'key' => 'fund_220_realizedgainslossesytd',
                    'show' => 'conditional'
                ],[
                    'order' => 3,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Unrealized Gains/Losses',
                    'key' => 'fund_225_unrealizedgainslosses',
                    'show' => 'conditional'
                ],[
                    'order' => 4,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Realized Gain/Losses on Stock Gifts',
                    'key' => 'fund_272_realized_gainloss_gifts',
                    'show' => 'conditional'
                ],[
                    'order' => 5,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Investment Management Fee',
                    'key' => 'fund_273_total_inv_mgmt_fees',
                    'show' => 'conditional'
                ],
            ],
            'show' => 'always'
        ],
        [
            'order' => 5,
            'type' => 'single',
            'subtype' => 'self-link',
            'name' => 'Pending Disbursements',
            'key' => 'fund_pending_disbursements',
            'link' => 'pending-disbursements',
            'show' => 'always'
        ],
    ];

    // fund_520_endingbalanceytd
    static $endBalanceMap = [
        'type' => 'single',
        'subtype' => 'default',
        'name' => 'ENDING ASSETS BALANCE',
        'key' => 'fund_520_endingbalanceytd',
        'show' => 'always'
    ];
}

class FFPStatement
{
    /**
     * asset pool children
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getAssetPoolChildren($fund, $map) {
        if (!isset($map['childrenHint'])) return null;
        $hint = $map['childrenHint'];
        $children = [];
        for ($i=1; $i<=$map['max']; ++$i) {
            // $key = $hint . '_' . $i . '_mv';
            $key = $hint . '' . $i . 'value';
            if ($fund[$key]) {
                $child = [];
                $child['type'] = "single";
                $child['order'] = $i;
                $child['key'] = $key;
                $child['amount'] = GnUtils::money($fund[$key]);
                $child['amount_raw'] = $fund[$key];
                // $key = $hint . '_' . $i . '_tick';
                $key = $hint . '' . $i . 'ticker';
                $child['link'] = $fund[$key];

                if ($child['link'] && strlen($child['link'])) {
                    $child['subtype'] = "fund-linked";
                }
                //$key = $hint . '_' . $i . '_lnm';
                $key = $hint . '' . $i . 'name';
                $child['name'] = $fund[$key];
                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * asset pool
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getAssetPool($fund, $map) {
        if ($map['show'] == 'never') return null;

        $amount = $fund[$map['key']];
        if ($map['show'] != 'always' && $amount == 0) return null; // $map['show'] == 'conditional'

        $pool = [];
        $pool['type'] = $map['type'];
        $pool['subtype'] = $map['subtype'];
        $pool['name'] = $map['name'];
        $pool['key'] = $map['key'];
        $pool['amount_raw'] = $amount;
        $pool['amount'] = GnUtils::money($amount);
        return $pool;
    }

    /**
     * fund asset pools
     *
     * @param $fund
     * @return array
     */
    private function getAssetPools($fund) {
        $pools = [];
        $maps = StatementItem::$poolsMap;

        foreach($maps as $map) {
            $pool = $this->getAssetPool($fund, $map);
            if (!$pool) continue;
            $pool['children'] = $this->getAssetPoolChildren($fund, $map);
            $pools[] = $pool;
        }
        return $pools;
    }

    /**
     * activity pool children
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getActivityPoolChildren($fund, $map)
    {
        if (!isset($map['children'])) return null;
        $childrenMap = $map['children'];
        $children = [];

        foreach($childrenMap as $cmap) {
            $amount = $fund[$cmap['key']];
            if ($cmap['show'] == 'conditional' && $amount == 0) continue;

            $child = [];
            $child['type'] = $cmap['type'];
            $child['subtype'] = $cmap['subtype'];
            $child['name'] = $cmap['name'];
            $child['key'] = $cmap['key'];
            $child['amount'] = GnUtils::money($amount);
            $children[] = $child;
        }
        return $children;
    }

    /**
     * activity section links
     *
     * @param $fund
     * @param $for
     * @return string URL
     */
    private function getActivityLink($fund, $for) {
        return '/m/' . $for . '/' . $fund->fund_id;
    }

    /**
     * get activity section items
     *
     * @param $fund
     * @param $map
     * @return array
     */
    private function getActivity($fund, $map) {
        if (!$map['show']) null;
        $activity = [];
        $activity['type'] = $map['type'];
        $activity['subtype'] = $map['subtype'];

        $amount = $fund[$map['key']];
        if ($map['subtype'] == 'named-link' || $map['subtype'] == 'self-link') {
            $activity['name'] = $map['name'];
            $activity['link'] = $this->getActivityLink($fund, $map['link']);
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['key'] = $map['key'];
            $activity['amount'] = GnUtils::money($amount);
        } else {
            $activity['name'] = $map['name'];
            $activity['key'] = $map['key'];
            $activity['amount'] = GnUtils::money($amount);
        }
        return $activity;
    }

    /**
     * get fund activities
     *
     * @param $fund
     * @return array
     */
    private function getActivities($fund)
    {
        $activities = [];
        $maps = StatementItem::$activitiesMap;
        foreach ($maps as $map) {
            $activity = $this->getActivity($fund, $map);
            if ($activity) {
                $activity['children'] = $this->getActivityPoolChildren($fund, $map);
                $activities[] = $activity;
            }
        }
        return $activities;
    }

    /**
     * get default fund value
     *
     * @param $fund
     * @param $map
     * @return array
     */
    private function getDefault($fund, $map)
    {
        $value = [];
        $value['type'] = $map['type'];
        $value['subtype'] = $map['subtype'];
        $value['name'] = $map['name'];
        $value['key'] = $map['key'];
        $value['amount_raw'] = $fund[$map['key']];
        $value['amount'] = GnUtils::money($fund[$map['key']]);
        return $value;
    }

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
        $fund = FFPFunds::getFundStatementFull($id);
        // $fund = FundStatement::getById($id);
        if (!$fund) abort('404', 'Fund statement not found');
        // $fund->statement_date = date('Y-m-d', strtotime($fund->thru_date . ' -1 Weekday'));

        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        $fund->statement_date = date('Y-m-d', strtotime($today . ' -1 Weekday'));

        $api = new Api();
        $fund->fund_pending_disbursements = $api->apiPendingDisbursementsTotal($id);

        // ************************************************************************************************** //
        // Group A - FUND BALANCE
        // ************************************************************************************************** //
        $group = [];
        $group['order'] = 1;
        $group['type'] = "group";
        $group['title'] = "FUND BALANCE";

        // items - total invested assets and pools
        $asset = $this->getDefault($fund, StatementItem::$assetMap);
        $pools = $this->getAssetPools($fund);

        // ************************************************************ //
        // held away assets
        $totalHeldAwayAssets = 0; // sum of held-away assets
        $heldAwayPools = $this->getHeldAwayAssetPools($fund);
        if (count($heldAwayPools)) {
            foreach($heldAwayPools as $item) {
                $totalHeldAwayAssets += $item['amount_raw'];
            }
            $pools = array_merge($pools, $heldAwayPools);
        }

        // ************************************************************ //
        // update "Total Invested Assets"
        $poolTotal = $asset['amount_raw'] + $totalHeldAwayAssets;
        $asset['amount'] =  GnUtils::money($poolTotal);

        // $group['items'] = array_merge([$asset], $pools);
        // $asset['children'] = $pools;
        $group['items'] = array_merge([$asset], $pools);
        $groups[] = $group;

        // ************************************************************************************************** //
        // Group B - FUND ACTIVITIES
        // ************************************************************************************************** //
        $group = [];
        $group['order'] = 2;
        $group['type'] = "group";
        $group['title'] = "FUND ACTIVITIES";

        // items - activities
        // item - beginning asset balance
        $bab = $this->getDefault($fund, StatementItem::$abaBalanceMap);;

        // ************************************************************ //
        // update "Beginning Asset Balance"
        $babTotal = $bab['amount_raw'] + $totalHeldAwayAssets;
        $bab['amount'] =  GnUtils::money($babTotal);

        // groups items
        $group['items'] = array_merge([$bab], $this->getActivities($fund));
        $groups[] = $group;


        // Group C - ENDING ASSET BALANCE
        $group = [];
        $group['order'] = 3;
        $group['type'] = "balance";
        $group['title'] = "ENDING ASSETS BALANCE";

        // ending balance
        $balance = $this->getDefault($fund, StatementItem::$endBalanceMap);

        // update "ENDING ASSETS BALANCE"
        $balanceTotal = $balance['amount_raw'] + $totalHeldAwayAssets;
        $balance['amount'] =  GnUtils::money($balanceTotal);


        $group['items']= [$balance];
        $groups[] = $group;

        return ['data' => compact('fund', 'groups')];
    }

    /**
     * OPENFIN1CUST  variable show value Pershing as a parent
     * HELDWAY_SUM_BAL (We calculate all held-away variable value through code)
     * OPENFINTICK1, OPENFIN1MV, OPENFIN1DESC
     *
     * @param $fund
     * @return array
     */
    public function getHeldAwayAssetPools($fund)
    {
        // return $fund; // 'openfin14cust';
        $accounts = [];
        for ($i=1; $i<=100; ++$i) {
            $key = 'openfin'  . $i . 'cust';
            if (isset($fund[$key])) {
                $accounts[] = $fund[$key];
            }
        }
        $accounts = array_unique($accounts);
        if (!count($accounts)) return [];

        $maps = [];

        foreach($accounts as $account) {
            $maps[] = [
                'type' => 'pool',
                'subtype' => 'pool-indented',
                'name' => $account,
                'key' => 'openfin', // fund FUND_MONEYMARKETPOOLYTD',
                'children' => null,
                'childrenHint' => 'openfin', // MONEYMARKETPOOL_1_MV, MONEYMARKETPOOL_1_TICK, MONEYMARKETPOOL_1_LNM
                'max' => 100,
                'show' => 'conditional'
            ];
        }

        $pools = [];
        foreach($maps as $map) {
            $total = 0;
            $pool = $this->getHeldAwayAssetPool($fund, $map);
            if (!$pool) continue;
            $pool['children'] = $this->getHeldAwayAssetPoolChildren($fund, $map, $total);
            $pool['amount_raw'] = $total;
            $pool['amount'] = GnUtils::money($total);
            $pools[] = $pool;
        }
        return $pools;
    }

    /**
     * asset pool
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getHeldAwayAssetPool($fund, $map)
    {
        if ($map['show'] == 'never') return null;

        $amount = 0; // TODO: verify if 0 is OK or need to add add - $fund[$map['key']];
        // if ($map['show'] != 'always' && $amount == 0) return null;

        $pool = [];
        $pool['type'] = $map['type'];
        $pool['subtype'] = $map['subtype'];
        $pool['name'] = $map['name'];
        $pool['key'] = $map['key'];
        $pool['amount_raw'] = $amount;
        $pool['amount'] = GnUtils::money($amount);
        return $pool;
    }

    /**
     * asset pool children
     * OPENFINTICK1, OPENFIN1MV, OPENFIN1DESC
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getHeldAwayAssetPoolChildren($fund, $map, &$total)
    {
        if (!isset($map['childrenHint'])) return null;
        $hint = $map['childrenHint'];
        $children = [];
        for ($i=1; $i<=$map['max']; ++$i) {
            $nameKey = 'openfin'  . $i . 'cust';
            if ($map['name'] == $fund[$nameKey]) {
                $key = $hint . $i . 'mv';
                if ($fund[$key]) {
                    $total += $fund[$key];
                    $child = [];
                    $child['type'] = "single";
                    $child['subtype'] = "fund-linked";
                    $child['order'] = $i;
                    $child['key'] = $key;
                    $child['amount'] = GnUtils::money($fund[$key]);
                    $key = $hint . 'tick' . $i;
                    $child['link'] = $fund[$key];
                    $key = $hint . $i . 'desc';
                    $child['name'] = $fund[$key];
                    $children[] = $child;
                }
            }
        }

        return $children;
    }

}
