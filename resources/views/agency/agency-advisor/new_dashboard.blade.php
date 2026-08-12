@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container",
'agencyContainer' => "container history-container"])

@section('content')
   
    @include('common.page-header', [
        'pageTitle' => 'Financial Advisor Dashboard',
        'icon' => 'fas fa-chart-line',
        'hcXlWidth' => 12,
        'showRefresh' => true
    ])


{{-- {{dd($orderedWidgets);}} --}}
<style>
.canvasjs-chart-credit{
    display: none;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.dashboard-section .scrollable-content {
    max-height: 360px;
    overflow-y: auto;
    overflow-x: hidden;
}

@media screen and (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>
    <section class="content">
        
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="dashboard-grid">

                    @foreach($orderedWidgets as $widgetKey => $widgetView)
                        <div class="widget-container">
                            @include($widgetView)
                        </div>
                    @endforeach
        
                </div>
            </div>
        </div>
    </section>

    <!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->


    @include('agency.agency-advisor.common-script')

    <!-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->

    <script>
        function viewTicket(ticket_id) {

            window.location.href = 'ticket/view/' + ticket_id;
        }

        function editTicket(ticket_id) {

            window.location.href = 'dashboard-ticket/edit/' + ticket_id;
        }

        function getTicketByCharity(charity_id) {
            $.ajax({
                url: "{{ route('agency-dashboard') }}",
                method: 'GET',
                data: {
                    charity_id: charity_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update the open tickets section
                    $('#openTicketsContainer').html(response.openTicketsHTML);

                    // Store the ticket data globally
                    window.currentTicketData = response.ticketData;

                    // Update the chart with new data
                    generateChart($("#chart_id").val(), response.ticketData);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        function getrecommByCharity(charity_id) {
            $.ajax({
                url: "{{ route('agency-dashboard') }}",
                type: 'GET',
                data: {
                    // recom_charity_id: charity_id
                    recom_charity_id: charity_id === 'all' ? null : charity_id
                },
                success: function(response) {
                    $('#pending-recommendations-widget .scrollable-content').html(response
                        .pendingRecommendationsHTML);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recommendations:', error);
                }
            });
        }

        function getDAFAccountBySponsor(sponsor_id) {
            $.ajax({
                url: "{{ route('agency-dashboard') }}",
                type: 'GET',
                data: {
                    sponsor_id: sponsor_id === 'all' ? null : sponsor_id
                },
                success: function(response) {
                    $('#daf-account-summary-widget .scrollable-content').html(response
                        .dafAccountsHTML);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recommendations:', error);
                }
            });
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        function generateChart(type, ticketData = []) {
            if (type == '') {
                type = $("#chart_id").val();
            }

            // Use globally stored ticket data if not provided
            if (ticketData.length === 0) {
                ticketData = window.currentTicketData || [];
            }

            // Save the selected chart type to the database
            $.ajax({
                url: "{{ route('save.chart.preference') }}",
                method: 'POST',
                data: {
                    chart_type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.success);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving preference:', error);
                }
            });

            var chartData = [];
            ticketData.forEach(function(ticket) {
                chartData.push({
                    label: ticket.status_name,
                    y: ticket.total
                });
            });

            var options = {
                animationEnabled: true,
                theme: "light2",
                data: [{
                    type: type,
                    innerRadius: "30%",
                    legendText: "{label}",
                    indexLabel: "{label}: {y}",
                    dataPoints: chartData
                }]
            };

            var chart = new CanvasJS.Chart("chartContainer", options);
            chart.render();
        }

        // Initialize chart on page load
        document.addEventListener("DOMContentLoaded", function() {
            var preferredChartType = "{{ $preferredChartType }}";
            window.currentTicketData = @json($ticketArr); // Store the initial ticket data globally
            generateChart(preferredChartType, window.currentTicketData);
        });

        // Update chart when chart type is changed
        $("#chart_id").change(function() {
            var chartType = $(this).val();
            generateChart(chartType);
        });

    </script>

    


@endsection
