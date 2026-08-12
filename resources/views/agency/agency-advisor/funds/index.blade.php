<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Fund List";
?>
@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => 12])
<style>
.canvasjs-chart-credit{
    display: none;
}
</style>
<div class="container">
    <div class="form-wrapper form-last">
        <div class="row">
            <div class="col-xl-7 col-r-15">
                <h3 class="page-subtitle uppercase mt-2">
                    {{ \App\Models\ClientInfo::isHGA() ? "Fund Overview" : "Funds" }}
                </h3>
                @include('agency.agency-advisor.funds.fund-list-loader')
                <br>
            </div>
            <div class="col-xl-5 col-r-15">
                <h3 class="page-subtitle uppercase mt-2">
                    Funds Graph
                </h3>
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </div>
<script>
    $(function() {
        jsFundListLoader.init('/m/agency/list/funds/ajax');
        jsFundListLoader.runLoadData();
    });

    get_fund_for_graph();
    function get_fund_for_graph() {
    
        $.ajax({

            type: 'GET',
            url: "{{ route('agency-fund-list-ajax-graph') }}",
            dataType: 'json',
            success: function (data) {

                var dynamicDataPoints = [];
                for (var j in data) {

                    var funds = data[j];
                    var balance_format = funds.balance;
                    var fund_name = funds.name;

                     // Code for Graph
                    dynamicDataPoints.push({
                        label: fund_name,
                        y: balance_format
                    });
                }
                // Display Graph
                var options = {
                    animationEnabled: true,
                    title: {
                        text: '',
                        fontFamily: "Arial", 
                        fontSize: 18,
                    },
                    axisY: {
                        labelFormatter: function (e) {
                            return '$' + e.value.toLocaleString('en-US');
                        }
                    },
                    data: [{
                        type: "doughnut",
                        innerRadius: "30%",
                        showInLegend: true,
                        legendText: "{label}",
                        indexLabel: "{label}: ${y}",
                        dataPoints: dynamicDataPoints
                    }]
                };
                $("#chartContainer").CanvasJSChart(options);
                // End 
            }
        });
    }
</script>
@endsection
