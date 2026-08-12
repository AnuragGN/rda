<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 28-09-2019
 * Time: 21:55
 */

namespace App\GMF;

use App\Models\FundStatement;
use Auth;
use App\Models\Api;
use Illuminate\Http\Request;

// groups types  - 'group', 'info', 'balance'
// item types    - 'single', 'pool',
// item subtypes - (for pool) 'pool-default', 'pool-indented'
//               - (for single) 'default', 'named-link', 'self-link', 'fund-linked'

class Statement {

    // fund_74_totalassetsytd
    // fund_94_totalliabilitiesandfund
    // fund_100_beginningfundbalanceytd
    static $assetMap = [
        'order' => 1,
        'type' => 'single',
        'subtype' => 'default',
        'name' => 'beginning fund balances',
        'key' => 'beg_prin_bal',
        'show' => 'always'
    ];

    // beginning
    static $beginningMap = [
        [
            'order' => 1,
            'type' => 'pool',
            'subtype' => 'pool-default',
            'name' => 'Total Beginning Fund Balance',
            'key' => 'beg_total_bal',
            'show' => 'always',
            'children' => [
                [
                    'order' => 1,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Principle',
                    'key' => 'beg_prin_bal',
                    'show' => 'always'
                ],[
                    'order' => 2,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Grants Payable',
                    'key' => 'beg_gp',
                    'show' => 'always'
                ],[
                    'order' => 3,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Income available to spend from prior years',
                    'key' => 'beg_inc_bal',
                    'show' => 'always'
                ],[
                    'order' => 4,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Cash surrender value of life insurance policy',
                    'key' => 'beg_csv',
                    'show' => 'always'
                ],
            ],
        ],
    ];

    // additions
    static $additionsMap = [
        [
            'order' => 1,
            'type' => 'pool',
            'subtype' => 'pool-default',
            'name' => 'Total Additions',
            'key' => 'total_adds',
            'show' => 'always',
            'children' => [
                [
                    'order' => 1,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Contributions',
                    'key' => 'contribs',
                    'show' => 'always'
                ],[
                    'order' => 2,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Other interest and dividends',
                    'key' => 'misc_int_div',
                    'show' => 'always'
                ],[
                    'order' => 3,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Gains/losses-stock gifts or other assets',
                    'key' => 'stock_gl',
                    'show' => 'always'
                ],[
                    'order' => 4,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Net activity of investment pool',
                    'key' => 'net_inv_activity',
                    'show' => 'always'
                ],[
                    'order' => 4,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Other additions',
                    'key' => 'other_adds',
                    'show' => 'always'
                ],

            ],
        ],
    ];

    // distributions
    static $distributionsMap = [
        [
            'order' => 1,
            'type' => 'pool',
            'subtype' => 'pool-default',
            'name' => 'Total Distributions',
            'key' => 'total_dist',
            'show' => 'always',
            'children' => [
                [
                    'order' => 1,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Grants approved',
                    'key' => 'grants',
                    'show' => 'always'
                ],[
                    'order' => 2,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Administrative management fees',
                    'key' => 'misc_int_div',
                    'show' => 'fees'
                ],[
                    'order' => 3,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Other distributions',
                    'key' => 'other_dist',
                    'show' => 'always'
                ],

            ],
        ],
    ];

    // ending
    static $endingMap = [
        [
            'order' => 1,
            'type' => 'pool',
            'subtype' => 'pool-default',
            'name' => 'Total Ending Fund Balance',
            'key' => 'end_total_bal',
            'show' => 'always',
            'children' => [
                [
                    'order' => 1,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Ending Fund Balances',
                    'key' => 'end_prin_bal',
                    'show' => 'always'
                ],[
                    'order' => 2,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Grants Outstanding',
                    'key' => 'end_gp',
                    'show' => 'fees'
                ],[
                    'order' => 3,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Income available to spend',
                    'key' => 'end_inc_bal',
                    'show' => 'always'
                ],[
                    'order' => 4,
                    'type' => 'single',
                    'subtype' => 'default',
                    'name' => 'Cash surrender value of life insurance policy',
                    'key' => 'end_csv',
                    'show' => 'always'
                ],

            ],
        ],
    ];


    static $projectedMap = [
        [
            'order' => 1,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Income available to spend as of',
            'name_ext' => 'end_date',
            'key' => 'end_inc_bal',
            'show' => 'always'
        ],[
            'order' => 2,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Grant payments scheduled for',
            'name_ext' => 'grant_sched_year',
            'key' => 'current_year_gp',
            'show' => 'always'
        ],[
            'order' => 3,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Projected net income for',
            'name_ext' => 'project_inc_text',
            'key' => 'project_inc_amount',
            'show' => 'always'
        ],[
            'order' => 4,
            'type' => 'single',
            'subtype' => 'default',
            'name' => 'Total projected net income available for grants through',
            'name_ext' => 'ttl_proj_inc_text',
            'key' => 'ttl_proj_inc_amount',
            'show' => 'always'
        ],
    ];

    // pending grants - unused
    static $pendingGrantsMap = [
        'order' => 5,
        'type' => 'single',
        'subtype' => 'self-link',
        'name' => 'Pending Disbursements',
        'key' => 'fund_pending_disbursements',
        'link' => 'pending-disbursements',
        'show' => 'always'
    ];
}

class GMFStatement
{
    /**
     * activity pool children
     *
     * @param $fund
     * @param $map
     * @return array|null
     */
    private function getGroupItemPoolChildren($fund, $map)
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
            $child['amount'] = $amount;
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
    private function getGroupItemLink($fund, $for) {
        return '/' . $for . '/' . $fund->fund_id;
    }

    /**
     * get activity section items
     *
     * @param $fund
     * @param $map
     * @return array
     */
    private function getGroupItem($fund, $map) {
        if (!$map['show']) null;
        $activity = [];
        $activity['type'] = $map['type'];
        $activity['subtype'] = $map['subtype'];

        if ($map['subtype'] == 'named-link' || $map['subtype'] == 'self-link') {
            $activity['name'] = $map['name'];
            $activity['link'] = $this->getGroupItemLink($fund, $map['link']);
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['linkTitle'] = isset($map['linkTitle']) ? $map['linkTitle'] : '';
            $activity['key'] = $map['key'];
            $activity['amount'] = $fund[$map['key']];
        } else {
            $activity['name'] = $map['name'];

            if (isset($map['name_ext']) && strlen($map['name_ext']) > 0) {
                $ext = $fund[$map['name_ext']];
                if ($ext) $activity['name'] .= ' ' . $ext;
            }

            $activity['key'] = $map['key'];
            $activity['amount'] = $fund[$map['key']];
        }
        return $activity;
    }

    /**
     * get fund activities
     *
     * @param $fund
     * @return array
     */
    private function getGroup($fund, $maps)
    {
        $activities = [];
        foreach ($maps as $map) {
            $activity = $this->getGroupItem($fund, $map);
            if ($activity) {
                $activity['children'] = $this->getGroupItemPoolChildren($fund, $map);
            }
            $activities[] = $activity;
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
        // $value['amount'] = GnUtils::money($fund[$map['key']]);
        $value['amount'] = $fund[$map['key']];
        return $value;
    }

    /**
     * API get statement
     *
     * @param Request $request
     * @param null $id
     * @return array
     */
    public function apiFundStatement($id, Request $request)
    {
        $groups = [];
        $fund = FundStatement::getById($id);

        $api = new Api();
        $fund->fund_pending_disbursements = $api->apiPendingDisbursementsTotal($id);

        // Group A - FUND BEGINNING
        $group = [];
        $group['order'] = 1;
        $group['type'] = "group";
        // $group['title'] = "Advised";

        // items - activities
        $maps = Statement::$beginningMap;
        $a = $this->getGroup($fund, $maps);

        $maps = Statement::$additionsMap;
        $b = $this->getGroup($fund, $maps);

        $maps = Statement::$distributionsMap;
        $c = $this->getGroup($fund, $maps);

        $maps = Statement::$endingMap;
        $d =  $this->getGroup($fund, $maps);

        $group['items'] = array_merge($a, $b, $c, $d);
        $groups[] = $group;

        // Group B - TEXT
        $group = [];
        $group['order'] = 2;
        $group['type'] = "info";
        $group['title'] = "The box below applies to endowment funds that follow the spending policy. To ensure endowed funds are available in perpetuity, these funds adhere to a spending policy where they spend a fraction of their market value each year. This information does not apply to Acorn and Pass Through funds, Supporting Foundations or funds with their own investment manager.";
        $groups[] = $group;


        // Group C - PROJECTED
        $group = [];
        $group['order'] = 3;
        $group['type'] = "group";
        $group['title'] = "Projected Income Available for Grants";

        // items - projected
        $maps = Statement::$projectedMap;
        $group['items'] = $this->getGroup($fund, $maps);
        $groups[] = $group;

        return ['data' => compact('fund', 'groups')];
    }

}
