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
