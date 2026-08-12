<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\JCF;

use App\Helpers\GnUtils;
use App\Models\Fund;
use App\Models\FundStatement;
use Auth;
use App\Models\Api;
use Carbon\Carbon;
use Illuminate\Http\Request;

// groups types  - 'group', 'balance'
// item types    - 'single', 'pool', 'pool-container', 'pool-jcf-raw'
// item subtypes - (for pool) 'pool-basic', 'pool-default', 'pool-indented'
//               - (for single) 'default', 'named-link', 'self-link', 'fund-linked'

class Statement {

    // fund_74_totalassetsytd
    // fund_94_totalliabilitiesandfund
    // fund_100_beginningfundbalanceytd

    // NOTE: 'Total Invested Assets' is collapsible i.e. kind of pool for JCF
    static $assetMap = [
        'order' => 1,
        'type' => 'pool-container',
        'subtype' => 'cls-default-bold',
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
            'childrenHint' => 'shorttermpool',
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
            'name' => 'Long Term ESG Focused Pool',
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
            'childrenHint' => 'endowmentpool',
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
        ], [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Money Market Pool',
            'key' => 'fund_moneymarketpoolytd', // fund FUND_MONEYMARKETPOOLYTD',
            'children' => null,
            'childrenHint' => 'moneymarketpool', // MONEYMARKETPOOL_1_MV, MONEYMARKETPOOL_1_TICK, MONEYMARKETPOOL_1_LNM
            'max' => 150,
            'show' => 'conditional'
        ]

    ];

    // CASHYTD
    static $cashYtdMap = [
        'order' => 100,
        'type' => 'single',
        'subtype' => 'cls-indented',
        'name' => 'Cash',
        'key' => 'cashytd',
        'show' => 'conditional'
    ];

    // CASHRECEIPTYTD -
    static $cashReceiptYtdMap = [
        'order' => 100,
        'type' => 'single',
        'subtype' => 'cls-indented',
        'name' => 'Cash Receipts',
        'key' => 'fund_114_misccashreciptsytd',
        'show' => 'conditional'
    ];

    // fund_250_totalinvestactivityytd: "0.53",
    // fund_390_netchangeytd: null,
    // fund_225_unrealizedgainslosses: "0.53",

    // activity beginning asset balance
    static $abaBalanceMap = [
        'order' => 1,
        'type' => 'single',
        'subtype' => 'cls-default-bold',
        'name' => 'Beginning Assets Balance',
        'key' => 'fund_100_beginningfundbalanceytd',
        'show' => 'always'
    ];

    // order 1 has been moved out of map
    static $activitiesMap = [
        [
            'order' => 2,
            'type' => 'single',
            'subtype' => 'default',
            // 'subtype' => 'named-link',
            'name' => 'Contributions',
            'key' => 'fund_127_total_contributions',
            // 'link' => 'gift-history',
            // 'linkTitle' => 'Show Gift History',
            'show' => 'always'
        ],
        [
            'order' => 3,
            'type' => 'single',
            'subtype' => 'default',
            // 'subtype' => 'named-link',
            'name' => 'Disbursements',
            'key' => 'fund_269_total_disbursements',
            // 'link' => 'grant-history',
            // 'linkTitle' => 'Show Grant History',
            'show' => 'always'
        ],
        [
            'order' => 4,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Foundation Support',
            'key' => 'fund_300_foundationsupportytd',
            'show' => 'conditional'
        ],
        [
            'order' => 5,
            'type' => 'pool',
            'subtype' => 'pool-jcf-raw',
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
            'order' => 100,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Cash Receipts',
            'key' => 'fund_114_misccashreciptsytd',
            'show' => 'conditional'
        ]
    ];

    // fund_520_endingbalanceytd
    static $endBalanceMap = [
        'type' => 'single',
        'subtype' => 'cls-end-balance',
        'name' => 'Ending Assets Balance',
        'key' => 'fund_520_endingbalanceytd',
        'show' => 'always'
    ];

    static $pendingDisbursements = [
        'type' => 'single',
        'subtype' => 'self-link',
        'name' => 'Pending Disbursements',
        'key' => 'fund_pending_disbursements',
        'link' => 'pending-disbursements',
        'show' => 'conditional'
    ];

}

class JCFStatement
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
            $key = $hint . '_' . $i . '_mv';
            if ($fund[$key]) {
                $child = [];
                $child['type'] = "single";
                $child['subtype'] = "fund-linked";
                $child['order'] = $i;
                $child['key'] = $key;
                $child['amount'] = GnUtils::moneyJCFS($fund[$key]);
                $key = $hint . '_' . $i . '_tick';
                $child['link'] = $fund[$key];
                $key = $hint . '_' . $i . '_lnm';
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
        $pool['amount'] = GnUtils::moneyJCFS($amount);
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
        $maps = Statement::$poolsMap;

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
            $child['amount'] = GnUtils::moneyJCFS($amount);
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
        if ($map['show'] == 'conditional' && $amount == 0) return null;

        if ($map['subtype'] == 'named-link' || $map['subtype'] == 'self-link') {
            $activity['name'] = $map['name'];
            $activity['link'] = $this->getActivityLink($fund, $map['link']);
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['key'] = $map['key'];
            $activity['amount'] = GnUtils::moneyJCFS($amount);
        } else {
            $activity['name'] = $map['name'];
            $activity['key'] = $map['key'];
            $activity['amount'] = GnUtils::moneyJCFS($amount);
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
        $maps = Statement::$activitiesMap;
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
        $value['amount'] = GnUtils::moneyJCFS($fund[$map['key']]);
        if ($map['subtype'] == 'named-link' || $map['subtype'] == 'self-link') {
            $value['link'] = $this->getActivityLink($fund, $map['link']);
            $value['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
        }
        return $value;
    }

    /**
     * API get statement
     * $request->date format is "mm-dd-yyyy" for JCF ThruDate
     *
     * @param Request $request
     * @param null $fid
     * @return array
     */
    public function apiFundStatement($fid, Request $request)
    {
        $thruDate = null;
        if ($request->date) {
            $date = str_replace('-', '/', $request->date); // change to US date format for parsing
            $date = date("Y-m-d", strtotime($date));
            $thruDate = date('Y-m-d', strtotime($date . ' +2 Weekday'));
        }

        // test
        // return FundStatement::whereDate('thru_date', '=', $conditions['thru_date'])->first();
        // return FundStatement::where('fund_id', 'ilike', $fid)->first();
        // $query = self::where('fund_id', 'ilike', $id);
        // if (count($params)) $query->where($params);
        // return $query->orderBy('date_entered', 'DESC')->orderBy('fund_statement_id', 'DESC')->first();
        // return $conditions;

        $groups = [];
        $fund = JCFFunds::getFundStatementFull($fid, $thruDate);
        if (!$fund) return null;;
        $fund->statement_date = date('Y-m-d', strtotime($fund->thru_date . ' -2 Weekday'));

        $api = new Api();
        $fund->fund_pending_disbursements = $api->apiPendingDisbursementsTotal($fid);

        // ************************************************************************************************** //
        // Group A - FUND BALANCE
        // ************************************************************************************************** //
        $group = [];
        $group['order'] = 1;
        $group['type'] = "group";
        $group['title'] = "FUND BALANCE";

        // items - total invested assets and pools
        $asset = $this->getDefault($fund, Statement::$assetMap);
        $pools = $this->getAssetPools($fund);

        // ************************************************************ //
        // held away children - Impact Investments
        $totalHeldAwayChildren = 0; // sum of held-away investments
        $heldAwayChildrenPool = $this->getHeldAwayChildrenPool($fund);
        if ($heldAwayChildrenPool) {
            $totalHeldAwayChildren = $heldAwayChildrenPool['amount_raw'];
            $pools = array_merge($pools, [$heldAwayChildrenPool]);
        }

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

        // GreenHill performance pools for right pane
        $ghPools = [];
        foreach($pools as $value) {
            $ghPool = [];
            if (!isset($value['key'])) continue;
            if ($value['type'] != 'pool') continue;

            $key = $value['key'];
            if ($key === 'fund_30_shorttermpoolytd') {
                $ghPool['accountId'] = 14001;
            } else if ($key === 'fund_40_midtermpoolytd') {
                $ghPool['accountId'] = 14003;
            } else if ($key === 'fund_42_longtermpoolytd') {
                $ghPool['accountId'] = 14004;
            } else if ($key === 'fund_46_ltippoolytd') {
                $ghPool['accountId'] = 14005;
            } else if ($key === 'fund_50_impactinvestmntpool') {
                $ghPool['accountId'] = 14006;
            } else if ($key === 'fund_44_endowmentpoolytd') {
                $ghPool['accountId'] = 14007;
            } else if ($key === 'fund_48_highequitypoolytd') {
                $ghPool['accountId'] = 14008;
            } else {
                continue;
            }

            $ghPool['accountType'] = 'pool';
            $ghPool['key'] = $key;
            $ghPool['name'] = $value['name'];
            $ghPools[] = $ghPool;
            break; // we need only one pool
        }

        // ************************************************************ //
        // cash amount
        $cashAmount = 0;
        $cash = $this->getDefault($fund, Statement::$cashYtdMap);
        if ($cash['amount_raw'] != 0) {
            $cashAmount = $cash['amount_raw'];
            $pools = array_merge($pools, [$cash]);
        }

        // $receiptsAmount = 0;
        // $cashReceipt = $this->getDefault($fund, Statement::$cashReceiptYtdMap);
        // if ($cashReceipt['amount_raw'] != 0) {
        //    $receiptsAmount = $cashReceipt['amount_raw'];
        //    $pools = array_merge($pools, [$cashReceipt]);
        // }

        // ************************************************************ //
        // update "Total Invested Assets"
        $poolTotal = $asset['amount_raw'] + $totalHeldAwayChildren + $totalHeldAwayAssets + $cashAmount;
        // $poolTotal = $asset['amount_raw'] + $totalHeldAwayChildren + $totalHeldAwayAssets + $cashAmount + $receiptsAmount;

        $asset['amount'] =  GnUtils::moneyJCFS($poolTotal);

        // $group['items'] = array_merge([$asset], $pools);
        $asset['children'] = $pools;
        $group['items'][] = $asset;
        $groups[] = $group;


        // ************************************************************************************************** //
        // Group B - FUND ACTIVITIES
        // ************************************************************************************************** //
        $xFunds = [
            "ATKIN",
            "CHOR3", "CHOR3OF",
            "COHN2A",
            "CUSH",
            "FOST6",
            "FRED1OF",
            "GALI9",
            "GLIC",
            "HAZA",
            "JACO1", "JACO10F",
            "LIPI2",
            "LITT",
            "POLI5", "POLI5OF",
            "RIVKOF",
            "SCHE4", "SCHE4OF",
            "SILB",
            "SOLO",
            "STON3",
            "STRA",
            "VITE2", "VITE2OF",
            "ZALIK", "ZALIKOF",
            "JAFFE3"
        ];

        // do not show fund activity for xFunds!
        if ( !in_array(strtoupper($fid), $xFunds)) {
            $group = [];
            $group['order'] = 2;
            $group['type'] = "group";
            $group['title'] = "FUND ACTIVITIES";
            $group['title-sm-right'] = "YEAR-TO-DATE";

            // items - activities
            // item - beginning asset balance
            $bab = $this->getDefault($fund, Statement::$abaBalanceMap);;

            // ************************************************************ //
            // update "Beginning Asset Balance"
            $babTotal = $bab['amount_raw'] + $totalHeldAwayChildren + $totalHeldAwayAssets;
            if (strtoupper($fid) === 'JAFFE3') {
                $today = Carbon::today();
                $targetDate = Carbon::parse('2024-01-01');
                if ($today < $targetDate) $babTotal = 0;
            }

            $bab['amount'] = GnUtils::moneyJCFS($babTotal);

            // groups items
            $items = array_merge([$bab], $this->getActivities($fund));

            // groups items
            // array_push($items, $balance);
            $group['items'] = $items;
            $groups[] = $group;
        }
        // ************************************************************************************************** //
        // Group C - ENDING BALANCE
        // ************************************************************************************************** //
        $group = [];
        $group['order'] = 3;
        $group['type'] = "group";
        $group['title'] = "";

        // ending balance
        $balance = $this->getDefault($fund, Statement::$endBalanceMap);

        // update "ENDING ASSETS BALANCE"
        $balanceTotal = $balance['amount_raw'] + $totalHeldAwayChildren + $totalHeldAwayAssets;
        $balance['amount'] =  GnUtils::moneyJCFS($balanceTotal);

        $group['items'] = [$balance];
        $groups[] = $group;

        $disbursements = $this->getDefault($fund, Statement::$pendingDisbursements);
        if ($disbursements['amount_raw'] != 0) {
            $group = [];
            $group['order'] = 4;
            $group['type'] = "group-empty";
            $group['title'] = "";
            $group['items'] = [];
            $groups[] = $group;

            $group = [];
            $group['order'] = 5;
            $group['type'] = "group";
            $group['title'] = "";
            $group['items'] = [$disbursements];
            $groups[] = $group;
        }

        return ['data' => compact('fund', 'groups', 'ghPools')];
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
            $pool['amount'] = GnUtils::moneyJCFS($total);
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
        $pool['name'] = ($map['name'] === 'baaapi') ? 'Schwab' : $map['name'];
        $pool['key'] = $map['key'];
        $pool['amount_raw'] = $amount;
        $pool['amount'] = GnUtils::moneyJCFS($amount);
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
                    $child['amount'] = GnUtils::moneyJCFS($fund[$key]);
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


    /**
     * held away children - Impact Investments
     *
     * @param $fund
     * @return array
     */
    private function getHeldAwayChildrenPool($fund)
    {
        $total = 0;
        $children = [];
        $records = JCFFunds::getHeldAwayImpactFundsByFundId($fund->fund_id);
        if (!count($records)) return null;

        foreach($records as $i => $record){
            $child = [];
            $total += $record['mv'];
            $child['type'] = "single";
            $child['subtype'] = "default";
            $child['order'] = $i;
            $child['amount'] = GnUtils::moneyJCFS($record['mv']);
            $child['name'] = $record['desc'];
            $children[] = $child;
        }

        $pool = [
            'type' => 'pool',
            'subtype' => 'pool-indented',
            'name' => 'Impact Investments',
            'key' => 'dummy_key_held_away_01',
            'children' => $children,
            'show' => 'conditional',
            'amount_raw' => $total,
            'amount' => GnUtils::moneyJCFS($total)
        ];
        return $pool;
    }

    static public function getDisplayableStatementDate(Fund $fund)
    {
        $statement =  FundStatement::getById($fund->fund_id);
        if (!$statement) return '';
        $statementDate = date('Y-m-d', strtotime($statement->thru_date . ' -2 Weekday'));
        return GnUtils::customDate($statementDate);
    }
}
