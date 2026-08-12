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
        'CHARITABLE_CATALOG' => 'Charitable Catalog',
        'FUND_DOCUMENTS' => 'Fund Documents',
        'FUND_STATEMENTS' => 'Fund Statements',
        'DAF_APPLICATION_FORM' => 'DAF Application Form',
    ],
    'message' => [
        'DATA_NOT_FOUND' => "No data available!"
    ],
    'value' => [
        'FS_POOL_COLLAPSED' => false,
        'MIN_GRANT_AMOUNT' => 250,
        'MIN_CONTRIBUTION_AMOUNT' => 100,
        'CC_FEE_ENABLED' => false,
        'CC_FEE' => 3,
        'DAF_MAX_ADDITIONAL_DONOR' => 4,
        'DAF_MAX_SUCCESSORS_INDIVIDUALS' => 4,
        'DAF_MAX_SUCCESSORS_ORGANIZATIONS' => 4,
        'DAF_MIN_CONTRIBUTION_CC_AMOUNT' => 1,
        'DAF_MAX_CONTRIBUTION_SECURITIES' => 2,
        'DAF_MAX_CONTRIBUTION_STOCKS' => 2
    ],
    'feature' => [
        'PUBLIC_DONATIONS' => false,
        'CONTRIBUTION' => true, // Gift / Contribution payment
        'STRIPE_PAYMENT' => false,
        'RECENT_CONTRIBUTIONS' => true,  // Recent Online Gifts
        'CHARITABLE_CATALOG' => false,
        'APP_PROFILE' => false,
        'SOCIAL_SHARE_ORG' => false,
        'GRANTING_FREQUENCY' => true,
        'INVESTMENTS' => true,
        'FUND_N_POOL_PERFORMANCE' => false,
        'AUTH_2FA' => false,
        'GUIDE_STAR_CANDID' => true,
        'MY_DOCUMENTS' => true,
        'MY_STATEMENTS' => false,
        'DAF_REGISTRATION' => true,
        'SESSION_TIMEOUT' => true,
        'FUND_ADVISORS' => true
    ],
    'donor_docs_writable' => [
    ],
    'donor_docs_readable' => [
        'FUND-STATEMENT' => 'Fund Statements',
        'DONOR-AGREEMENT' => 'Agreement/Applications',
        'DONOR-CONTRIBUTION' => 'Contributions',
        'DONOR-GRANT-ACK' => 'Grant Acknowledgments',
    ],

];
