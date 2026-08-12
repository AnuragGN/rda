@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container",
'agencyContainer' => "container history-container"])

@section('content')
   
    @include('common.page-header', [
        'pageTitle' => 'Financial Advisor Dashboard',
        'icon' => 'fas fa-chart-line',
        'hcXlWidth' => 12,
        'showRefresh' => true
    ])
	
<style>
    .dashboard-container {
        margin: 0;
        padding: 0;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        font-size: 24px;
        color: #333;
        margin: 0;
    }

    .dashboard-header button {
        background-color: #0097b2;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .dashboard-header button:hover {
        background-color: #007a8f;
    }

    .dashboard-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0px;
        margin-bottom: 20px;
    }

    .col-6 {
        flex: 0 0 calc(50% - 10px);
        width: calc(50% - 10px);
    }

    .col-12 {
        flex: 0 0 100%;
        width: 100%;
    }

    .dashboard-card {
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .dashboard-card-header {
        background-color: #0e7490;
        color: white;
        padding: 10px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }


    .view-all-link {
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
    }

    .view-all-link:hover {
        color: #e0f4f7;
    }

    /* Sponsor Pool Styles */
    .sponsor-pool {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .sponsor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        background-color: #f9f9f9;
        cursor: pointer;
        user-select: none;
        border-bottom: 1px solid #e0e0e0;
    }

    .sponsor-header:hover {
        background-color: #f5f5f5;
    }

    .sponsor-title {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .sponsor-toggle {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0097b2;
        font-size: 18px;
        transition: transform 0.3s ease;
        font-weight: 600;
    }

    .sponsor-toggle.collapsed {
        transform: rotate(0deg);
    }

    .sponsor-toggle.expanded {
        transform: rotate(180deg);
    }

    .sponsor-amount {
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        margin-left: 10px;
    }

    .sponsor-funds {
        padding: 12px 15px;
    }

    .fund-row {
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 3px;
        padding: 12px;
        margin-bottom: 10px;
    }

    .fund-row:last-child {
        margin-bottom: 0;
    }

    .fund-row-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .fund-row-name {
        font-weight: 600;
        color: #333;
        flex: 1;
    }

    .fund-row-amount {
        color: #666;
        white-space: nowrap;
    }

    .fund-row-links {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #f0f0f0;
    }

    .fund-row-link {
        font-size: 12px;
        color: #0097b2;
        text-decoration: none;
    }

    .fund-row-link:hover {
        text-decoration: underline;
    }

    /* Account Filter */
    .account-filter {
        margin-bottom: 15px;
    }

    .filter-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        display: block;
    }

    .dashboard-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
    }

    .account-item {
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .account-item:last-child {
        border-bottom: none;
    }

    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .account-name {
        font-weight: 600;
        color: #333;
    }

    .account-amount {
        font-weight: 600;
        color: #0097b2;
    }

    .account-status {
        font-size: 12px;
        color: #666;
    }

    /* Recommendations */
    .recommendation-item {
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .recommendation-item:last-child {
        border-bottom: none;
    }

    .recommendation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .recommendation-name {
        font-weight: 600;
        color: #333;
        flex: 1;
    }

    .recommendation-amount {
        font-weight: 600;
        color: #666;
        white-space: nowrap;
        margin-left: 10px;
    }

    .recommendation-meta {
        font-size: 11px;
        color: #999;
        line-height: 1.4;
    }

    .badge {
        display: inline-block;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 3px;
        margin-right: 5px;
    }

    .badge-success {
        background-color: #e6f7f5;
        color: #00796b;
    }

    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-error {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Institutional Claims Table */
    .claims-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .claims-table th {
        background-color: #f5f5f5;
        padding: 9px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
    }

    .claims-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e0e0e0;
        color: #333;
    }

    .claims-table tr:hover {
        background-color: #f9f9f9;
    }

    /* Service Requests */
    .service-chart {
        text-align: center;
        padding: 20px;
    }

    .pie-chart {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, #4a5f8f 0%, #334477 100%);
        margin: 0 auto 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .service-stats {
        text-align: left;
        font-size: 13px;
        color: #666;
    }

    .service-stats-item {
        padding: 8px 0;
    }

    .service-stats-item .label {
        display: inline-block;
        width: 120px;
    }

    .service-stats-item .count {
        font-weight: 600;
        color: #333;
    }

    .service-list {
        padding: 0;
    }

    .service-request-item {
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .service-request-item:last-child {
        border-bottom: none;
    }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .request-title {
        font-weight: 600;
        color: #333;
        flex: 1;
    }

    .request-icons {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .icon-btn.blue {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .icon-btn.green {
        background-color: #e8f5e9;
        color: #388e3c;
    }

    .request-meta {
        font-size: 12px;
        color: #999;
        line-height: 1.5;
    }

    .request-overview {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }

    .request-overview h3 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 16px;
    }

    .overview-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .overview-item:last-child {
        border-bottom: none;
    }

    .overview-label {
        color: #666;
    }

    .overview-value {
        font-weight: 600;
        color: #333;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .col-6 {
            flex: 0 0 100%;
            width: 100%;
        }

        .service-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 768px) {
        .dashboard-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .request-header {
            flex-direction: column;
            gap: 10px;
        }

        .sponsor-header {
            flex-wrap: wrap;
        }
    }
</style>

 <section class="content">
	<div class="container">
		<div class="form-wrapper form-last">
            <div class="dashboard-row">
                @foreach($orderedWidgets as $widgetKey => $widgetView)
                    @include($widgetView)
                @endforeach
            </div>
		</div>
    </div>
</section>
@include('agency.agency-advisor.common-script') 
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
   
   /*
    ** Function to toggle sponsor fund details
    */

    function toggleSponsor(headerElement) 
    {
        const toggle = headerElement.querySelector('.sponsor-toggle');
        const funds = headerElement.parentElement.querySelector('.sponsor-funds');
        
        toggle.classList.toggle('collapsed');
        toggle.classList.toggle('expanded');
        
        if (funds.style.display === 'none') {
            funds.style.display = 'block';
        } else {
            funds.style.display = 'none';
        }
    }
    
    /*
    ** AJAX Functions to fetch DAF Application data based on filters
    */

    function getDAFAccountBySponsor(sponsor_id) 
    {
        $.ajax({
            url: "{{ route('agency-dashboard') }}",
            type: 'GET',
            data: {
                sponsor_id: sponsor_id === 'all' ? null : sponsor_id
            },
            success: function(response) {
                $('#daf-account-summary-widget').html(response
                    .dafAccountsHTML);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching recommendations:', error);
            }
        });
    }
    
    /*
    ** AJAX Functions to fetch Pending Recommendation data based on filters
    */

    function getrecommByCharity(charity_id) 
    {
        $.ajax({
            url: "{{ route('agency-dashboard') }}",
            type: 'GET',
            data: {
                // recom_charity_id: charity_id
                recom_charity_id: charity_id === 'all' ? null : charity_id
            },
            success: function(response) {
                $('#pending-recommendations-widget').html(response
                    .pendingRecommendationsHTML);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching recommendations:', error);
            }
        });
    }
    
    /*
    ** View Ticket Details
    */

    function viewTicket(ticket_id) 
    {
        let url = "{{ route('agency-service-ticket-view', ':id') }}";
        url = url.replace(':id', ticket_id);
        window.location.href = url;
    }
    
    /*
    ** AJAX Functions to fetch Ticket data based on sponsor filter
    */

    function getTicketBySponsor(sponsor_id) 
    {
        let chartId = $("#chart_id").val();

        $.ajax({
            url: "{{ route('agency-dashboard') }}",
            method: 'GET',
            data: {
                sponsor_id: sponsor_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) 
            {
                // Update the open tickets section
                $('#openTicketsContainer').html(response.openTicketsHTML);

                // Store the ticket data globally
                window.currentTicketData = response.ticketData;

                // Update the chart with new data
                generateTicketChart(chartId, response.ticketData);
            },
            error: function(xhr, status, error) 
            {
                console.error('Error fetching data:', error);
            }
        });
    }
    
    let ticketChart = null;

    /*
    ** Onload generate initial chart with default data passed from controller
    */

    document.addEventListener("DOMContentLoaded", function() 
    {
        var preferredChartType = "{{ $preferredChartType }}";
        window.currentTicketData = @json($ticketArr); 
        generateTicketChart(preferredChartType, window.currentTicketData);
    });

</script>

@endsection