<?php

return [
    'date_format' => "m-d-Y",
    'text' => [
        'FUND_OVERVIEW' => 'Fund Balance', // Overview
        'MAKE_A_GIFT' => 'Contribute', // Gift / Contribution
        'MAKE_A_GRANT' => 'Recommend a Grant', // Make/Recommend a Grant
        'MAKE_ANOTHER_GIFT' => 'Make another Contribution', // Gift / Contribution
        'GIFTS_BY_MONTH' => 'Contributions by Month',
        'GIFT_HISTORY' => 'Contribution History',
        'RECENT_CONTRIBUTIONS' => 'Recent Online Contributions',
        'INVESTMENTS' => 'Investments',
        'CHARITABLE_CATALOG' => 'Progressive Jewish Fund Grantees',
        'FUND_STATEMENTS' => 'Historic Fund Statements'
    ],
    'message' => [
        'DATA_NOT_FOUND' => "No data available!"
    ],
    'value' => [
        'FS_POOL_COLLAPSED' => true,
        'MIN_GRANT_AMOUNT' => 100,
        'MIN_CONTRIBUTION_AMOUNT' => 100,
        'CC_FEE_ENABLED' => true,
        'CC_FEE' => 3,
        'DAF_MAX_ADDITIONAL_DONOR' => 4,
        'DAF_MAX_SUCCESSORS_INDIVIDUALS' => 4,
        'DAF_MAX_SUCCESSORS_ORGANIZATIONS' => 4,
   	    'DAF_MIN_CONTRIBUTION_CC_AMOUNT' => 1,
        'DAF_MAX_CONTRIBUTION_SECURITIES' => 5
    ],
    'feature' => [
        'PUBLIC_DONATIONS' => false,
        'CONTRIBUTION' => true, // Gift / Contribution payment
        'STRIPE_PAYMENT' => false,
        'RECENT_CONTRIBUTIONS' => false,  // Recent Online Gifts
        'CHARITABLE_CATALOG' => true,
        'APP_PROFILE' => true,
        'SOCIAL_SHARE_ORG' => false,
        'GRANTING_FREQUENCY' => true,
        'INVESTMENTS' => true,
        'FUND_N_POOL_PERFORMANCE' => false,
        'AUTH_2FA' => false,
        'GUIDE_STAR_CANDID' => true,
        'MY_DOCUMENTS' => false,
        'MY_STATEMENTS' => true
    ],
];
