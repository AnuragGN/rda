<?php

return [
    'date_format' => "m-d-Y",
    'text' => [
        'FUND_OVERVIEW' => 'Fund Overview', // Overview
        'MAKE_A_GIFT' => 'Make a Contribution', // Gift / Contribution
        'MAKE_A_GRANT' => 'Make a Grant', // Make/Recommend a Grant
        'MAKE_ANOTHER_GIFT' => 'Make another Contribution', // Gift / Contribution
        'GIFTS_BY_MONTH' => 'Contributions by Month',
        'GIFT_HISTORY' => 'Contribution History',
        'RECENT_CONTRIBUTIONS' => 'Recent Online Contributions',
        'INVESTMENTS' => 'Investment Selections',
        'CHARITABLE_CATALOG' => 'Charitable Catalog',
        'FUND_DOCUMENTS' => 'Fund Documents'
    ],
    'message' => [
        'DATA_NOT_FOUND' => "No data available!"
    ],
    'value' => [
        'FS_POOL_COLLAPSED' => false,
        'MIN_GRANT_AMOUNT' => 100,
        'MIN_CONTRIBUTION_AMOUNT' => 1,
        'CC_FEE_ENABLED' => true,
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
        'RECENT_CONTRIBUTIONS' => false,  // Recent Online Gifts
        'CHARITABLE_CATALOG' => false,
        'APP_PROFILE' => true,
        'SOCIAL_SHARE_ORG' => false,
        'GRANTING_FREQUENCY' => true,
        'INVESTMENTS' => true,
        'FUND_N_POOL_PERFORMANCE' => false,
        'AUTH_2FA' => false,
        'MY_DOCUMENTS' => true,
        'MY_STATEMENTS' => false,
        'DAF_REGISTRATION' => true
    ],
    'donor_docs_writable' => [
        // 'DONOR-AGREEMENT' => 'Agreement/Application',
    ],
    'donor_docs_readable' => [
        'DONOR-AGREEMENT' => 'Agreement/Applications',
        'ANNUAL-TAX-STATEMENT' => 'Annual Statements',
        'DONOR-CONTRIBUTION' => 'Contributions',
        'GENERAL-NOTIFICATIONS' => 'General Notifications',
        'DONOR-GRANT-ACK' => 'Grant Acknowledgments',
        'INVESTMENT-CHANGES' => 'Investment Changes',
        'INVESTMENT-CONFIRMATIONS' => 'Investment Confirmations',
        'FUND-STATEMENT' => 'Quarterly Statements',
    ],
];
