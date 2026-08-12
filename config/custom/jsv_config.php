<?php

return [
    'date_format' => "m-d-Y",
    'text' => [
        'FUND_OVERVIEW' => 'Fund Overview', // Overview
        'MAKE_A_GIFT' => 'Make a Gift', // Gift / Contribution
        'MAKE_A_GRANT' => 'Recommend a Grant', // Make/Recommend a Grant
        'MAKE_ANOTHER_GIFT' => 'Make another Gift', // Gift / Contribution
        'GIFTS_BY_MONTH' => 'Gifts by Month',
        'GIFT_HISTORY' => 'Gift History',
        'RECENT_CONTRIBUTIONS' => 'Recent Online Gifts',
        'INVESTMENTS' => 'Investments',
        'CHARITABLE_CATALOG' => 'Charitable Catalog'
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
    ],
    'feature' => [
        'PUBLIC_DONATIONS' => false,
        'CONTRIBUTION' => true, // Gift / Contribution payment
        'RECENT_CONTRIBUTIONS' => false,  // Recent Online Gifts
        'CHARITABLE_CATALOG' => true,
        'APP_PROFILE' => true,
        'SOCIAL_SHARE_ORG' => false,
        'GRANTING_FREQUENCY' => true,
        'INVESTMENTS' => false,
        'FUND_N_POOL_PERFORMANCE' => false,
        'AUTH_2FA' => false,
        'GUIDE_STAR_CANDID' => false,
        'MY_DOCUMENTS' => true,
        'MY_STATEMENTS' => false,
        'DAF_REGISTRATION' => false
    ],
    'donor_docs_writable' => [
        'DONOR-AGREEMENT' => 'Agreement/Application',
    ],
    'donor_docs_readable' => [
        'FUND-STATEMENT' => 'Fund Statements',
        'DONOR-AGREEMENT' => 'Agreement/Applications',
        'DONOR-CONTRIBUTION' => 'Contributions',
        'DONOR-GRANT-ACK' => 'Grant Acknowledgments',
    ],

];
