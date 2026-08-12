<?php
/**
 * Created by Lawkush.
 * Date: 2/18/2023
 * Time: 4:34 PM
 */
namespace App\Helpers;


class ReportData
{
    const MIN_REPORT_COLUMNS = 1;
    const MAX_REPORT_COLUMNS = 20;

    // Report type name - table name
    const REPORT_TYPE_FUND_GIFT_HISTORY = 'fund_gift_history';
    const REPORT_TYPE_FUND_GRANT_HISTORY = 'fund_grant_history';
    const REPORT_TYPE_SERVICE_TICKET = 'tickets';
    const REPORT_TYPE_CLIENT = 'contact';

    const REPORT_TYPE_LIST = [
        self::REPORT_TYPE_FUND_GIFT_HISTORY => 'Gift History',
        self::REPORT_TYPE_FUND_GRANT_HISTORY => 'Grant History',
        self::REPORT_TYPE_SERVICE_TICKET => 'Service Tickets',
        self::REPORT_TYPE_CLIENT => 'Clients'
    ];

    const REPORT_DURATION_OPTIONS = [
        'last_one_day' => 'Last One Day',
        'last_one_week' => 'Last One Week',
        'last_one_month' => 'Last One Month',
        'last_one_year' => 'Last One Year',
        'this_calendar_year' => 'This Calendar Year',
    ];

    const REPORT_OUTPUT_COLUMNS = [
        
        
        self::REPORT_TYPE_FUND_GIFT_HISTORY => 
        [
            'amount' => 'Amount',
            'date_entered' => 'Date entered',
            'donor' => 'Donor name',
            'fund_id' => 'Fund Name',
            'gift_date' => 'Gift date',
            'proposal_name' => 'Proposal name',
            'comment' => 'Comment',
            'last_updated' => 'Last updated',
        ],
        self::REPORT_TYPE_FUND_GRANT_HISTORY => 
        [
            'amount' => 'Amount',
            'date_entered' => 'Date entered',
            'fund_id' => 'Fund Name',
            'grantee' => 'Grantee',
            'grant_date' => 'Grant date',
            'payment_date' => 'Payment date',
            'grant_description' => 'Grant description',
            'last_updated' => 'Last updated',
        ],
        self::REPORT_TYPE_SERVICE_TICKET =>
        [
          /*  'title' => 'Subject',
            'description' => 'Description',
            'created_at' => 'Created at',
            'status' => 'Status',
            'priority' => 'Priority',
            'category' => 'Category',
            'closed_on' => 'Closed on',
	    'target_id' => 'Fund Name',*/
            'title' => 'Subject',
            'description' => 'Description',
            'category' => 'Ticket Type',
            'status' => 'Ticket Status',
            'priority' => 'Ticket Priority',
            'created_at' => 'Created At',
            'closed_at' => 'Closed At',
            //'target_type' => 'Fund Name',
            // 'sub_target_type' => 'Sub Target Type',
            // 'sub_target_id' => 'Sub Target Id',
        ],
        self::REPORT_TYPE_CLIENT =>
        [
            'contact_id' => 'Id',
            '_remote_id' => 'Remote Id',
            'first_name' => 'First name',
            'middle_name' => 'Middle name',
            'last_name' => 'Last name',
            'gender' => 'Gender',
            // 'last_updated' => 'Last updated',
            'phone' => 'Phone',
            'email' => 'Email',
        ]
    ];

    const REPORT_SEARCH_CRITERIA = [    //Confusion
        // self::REPORT_TYPE_CONTACT => [
        //     'search_name' => 'Name',
        //     'date_range' => 'Creation date range',
        //     'contact_type' => 'Contact type',
        //     'updated_date_range' => 'Updated date range',
        // ],
        // self::REPORT_TYPE_ORGANIZATION => [
        //     //'date_range' => 'Creation date range',
        //     'search_name' => 'Name',
        //     'updated_date_range' => 'Updated date range',
        //     'sync' => 'Sync',
        //     'visible' => 'Visible',
        //     'allow_recommendation' => 'Allow Recommendation',
        // ],

        self::REPORT_TYPE_FUND_GIFT_HISTORY => [
            'fund_id' => 'Name',
            'date_range' => 'Date range',
        ],
        self::REPORT_TYPE_FUND_GRANT_HISTORY => [
            'fund_id' => 'Name',
            'date_range' => 'Date range',
        ],
        self::REPORT_TYPE_SERVICE_TICKET => [
            'fund_id' => 'Name',
            'date_range' => 'Creation date range',
            // 'search_status' => 'By Status',
        ],
        self::REPORT_TYPE_CLIENT => [
            'fund_id' => 'Name',
            'date_range' => 'Creation date range',
            'contact_type' => 'Contact type',
        ]

    ];

    const REPORT_SORTING_ORDERS = [
        
        self::REPORT_TYPE_FUND_GIFT_HISTORY => [
            // 'fund_id' => 'Fund ID',
            'amount' => 'Amount'
        ],
        self::REPORT_TYPE_FUND_GRANT_HISTORY => [
            // 'fund_id' => 'Fund ID',
            'amount' => 'Amount'
        ],
        self::REPORT_TYPE_SERVICE_TICKET => [
            'id' => 'Ticket ID',
            'created_at' => 'Created at',
        ],
        self::REPORT_TYPE_CLIENT => [
            'first_name' => 'First Name',
            // 'created_on' => 'Creation Date',
        ]
    ];

    /**
     * @return array
     */
    public static function getReportDurationOptions()
    {
        $rdOptionsList = self::REPORT_DURATION_OPTIONS;

        $durationOptions = [];
        foreach ($rdOptionsList as $field => $label) {
            $durationOptions[$field] = $label;
        }

        return $durationOptions;

    }

    /**
     * @return array
     */
    public static function getReportTypeList()
    {
        $reportTypeList = self::REPORT_TYPE_LIST;
        $reportTypes = [];
        foreach ($reportTypeList as $field => $label) {
            $reportTypes[$field] = $label;
        }

        return $reportTypes;
    }

    /**
     * @return array
     */
    public static function getReportOutputColumns()
    {
        $outputColumnsList =  self::REPORT_OUTPUT_COLUMNS;
        $outputColumns = [];
        foreach ($outputColumnsList as $field => $label) {
            $outputColumns[$field] = $label;
        }

        return $outputColumns;
    }

    /**
     * @return array
     */
    public static function getReportSearchCriteria()
    {
        $searchCriteriaList =  self::REPORT_SEARCH_CRITERIA;
        $searchCriteria = [];
        foreach ($searchCriteriaList as $field => $label) {
            $searchCriteria[$field] = $label;
        }

        return $searchCriteria;
    }

    /**
     * @return array
     */
    public static function getReportSortingOrders()
    {
        $sortingOrderList = Self::REPORT_SORTING_ORDERS;
        $sortingOrders = [];

        foreach ($sortingOrderList as $key => $value) {
            $sortingOrders[$key] = $value;
        }

        return $sortingOrders;
    }

}
