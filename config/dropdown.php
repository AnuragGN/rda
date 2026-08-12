<?php
/**
 * Created by Anurag.
 * Date: 10/12/2023
 * Time: 12:37 PM
 */

return [
    //Ticketing System
    'status' => [
        'open' => 'Open',
        'in progress' => 'In Progress',
        'hold' => 'Hold',
        'closed' => 'Closed'
    ],
    'priority' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent'
    ],
    'category' => [
        'event' => 'Events',
        'meeting' => 'Meeting',
        'notes' => 'Notes',
        'raise cash' => 'Raise Cash',
        'rebalance portfolio' => 'Rebalance Portfolio',
		'advisor onboarding' => 'Advisor Onboarding'
    ],
    'support_staff_status' => [
        'pending' => 'Pending',
        'in progress' => 'In Progress',
        'done' => 'Done',
    ],
    //New Dashboard
    'chart_dropdown' => [
        'pie' => 'Pie Chart',
        'doughnut' => 'Doughnut Chart',
    ],
    //Event Management
    'event_type' => [
        'Conference' => 'Conference',
        'Workshop' => 'Workshop',
        'Webinar' => 'Webinar',
        'Seminar' => 'Seminar',
        'Meetup' => 'Meetup',
    ],
    //order widget
    'default_widget_order' => ['donor_fund_balance', 'service_requests', 'pending_client_recommendation', 'institutional_client', 'daf_account_summary'],

    // Add more dropdowns as needed.
];

