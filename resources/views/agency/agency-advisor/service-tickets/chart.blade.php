<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Ticket Charts";
?>
@extends (\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

@include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => 12])

<style>
.canvasjs-chart-credit{
    display: none;
}
.chart-list {
    margin: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
}
</style>
<div class="container history-container">
    <div class="form-wrapper form-last">
        <div class="row">
            <div class="col-xl-8 col-r-15">
                <div class="chart-display">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
            </div> 
            <div class="col-xl-4 col-r-15">
                <div class="chart-list">
                    <ul>
                        <li><a onclick="generateChart('pie')">Pie Chart</a></li>
                        <li><a onclick="generateChart('column')">Column Chart</a></li>
                        <li><a onclick="generateChart('line')">Line Chart</a></li>
                        <li><a onclick="generateChart('doughnut')">Doughnut Chart</a></li>
                        <li><a onclick="generateChart('bar')">Bar Chart</a></li>
                        <li><a onclick="generateChart('area')">Area Chart</a></li>
                    </ul>
                </div>
            </div> 
        </div>
    </div>
</div>   
<script>
generateChart('pie');
function generateChart(type) {
    var chartData = [
        { y: 15, label: "Open" },
        { y: 2, label: "In Progress" },
        { y: 3, label: "Hold" },
        { y: 6, label: "Closed" }
    ];

    var options = {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: type.charAt(0).toUpperCase() + type.slice(1) + " Chart"
        },
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
</script>
@endsection
