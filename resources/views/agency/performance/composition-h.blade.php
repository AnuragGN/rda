<?php
$composition = \App\Models\PerformanceData::getComposition($accountId, $accountType);

$clsPie = 'col-md-7';
$clsTable = 'col-md-5';
if (isset($sidePane) && $sidePane == true) {
    $clsPie = 'col-md-12 order-1';
    $clsTable = 'col-md-12 order-2';
}
//$compositionChartData = $composition ? $composition['pieChartData'] : [];
//$compositionChartTable = $composition ? $composition['pieChartTable'] : [];
?>

<div class="chart-box mt-4">

    @if(isset($sidePane) && $sidePane == true)
        <h4 class="page-title">
            <span>Total Pool Composition</span>
            <a href="{{route('donor-pool-performance')}}" style="font-size: 80%;">See more</a>
        </h4>
        <h4 class="pool-name">{{$poolName}}</h4>
    @else
        <h3 class="page-title mt-0">Total Pool Composition</h3>
    @endif

    <div class="tabs_oval" id="id_tabs_fund_performance">
        <ul class="nav nav-tabs">
            @foreach($composition as $j => $data)
                <li class="nav-item"><a class="nav-link  {{$j == 0 ? 'active' : ''}}" href="#id_pc_pc_{{$j}}" data-toggle="tab">{{$data['title']}}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($composition as $j => $data)
                <div class="tab-pane {{$j == 0 ? 'active' : 'manage-composition-display'}}" id="id_pc_pc_{{$j}}">

                    <div class="row chart-container chart">
                        <div class="{{$clsTable}}">
                            <br>
                            <table class="portfolio-table">
                                @foreach($data['pieChartTable'] as $i => $row)
                                    @if($i==0)
                                        <tr>
                                            <th>{{$row['0']}}</th>
                                            <th>{{$row['1']}}</th>
                                            <th>{{$row['2']}}</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{$row['0']}}</td>
                                            <td>{{$row['1']}}</td>
                                            <td>{{$row['2']}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                            <br>
                        </div>
                        <div class="{{$clsPie}}">
                            <canvas id="id_composition_pie_chart_{{$j}}" width="400" height="400"></canvas>
                        </div>
                        <div class="col-12 gh_data_source" style="text-align: right">
                            <small>Data Source: <a href="https://www.ghill.com/" target="_blank">GreenHill</a></small>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>

<script>
    $(function(){
        setTimeout(function() {
             $('.manage-composition-display').removeClass('manage-composition-display');
        }, 300);
    });

    $(function(){
        var piesData = <?=json_encode($composition)?>;
        console.log("piesData: ", piesData);

        piesData.forEach(function(value, index) {
            var pieData = piesData[index].pieChartData;
            console.log("pieData: ", pieData);

            var options = {};
            // options.scales = '$';
            var pie = new PieChartDS();
            pie.init(pieData, 'id_composition_pie_chart_' + index);
            pie.setOptions(options);
            // pie.setLegendPosition('right');
            pie.draw();
        });
    });
</script>
