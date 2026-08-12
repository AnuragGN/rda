<?php
if (!isset($fundComposition)) {
    $fundComposition = \App\Models\GhComposition::getFundCompositionData($id, false);
}
if ($fundComposition) {
    $endDate = $fundComposition['end_date'];
    $pieChartData = $fundComposition['pieChartData'];
    $pieChartTable = $fundComposition['pieChartTable'];
}
?>

@if($fundComposition)
    <div class="chart-box mt-2">
        <h4 class="page-title mt-0">
            <div>
                Total Pool Composition
                <span class="date" style="font-size: 15px;"><br/>As of {{$endDate}}</span>
            </div>
        </h4>
        <div class="chart-container chart">

            <div style="padding-left: 2rem; padding-right: 2rem;">
                <canvas id="id_gd_pie_1_chart" width="300" height="400"></canvas>
            </div>

            <br>
            <table class="portfolio-table">
                @foreach($pieChartTable as $i => $row)
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

        </div>

    </div>

    <script>
        $(function(){
            var pieData = <?=json_encode($pieChartData)?>;
            console.log("pieData: ", pieData);
            var options = {};
            // options.scales = '$';
            var pie = new PieChartDS();
            pie.init(pieData, 'id_gd_pie_1_chart');
            pie.setOptions(options);
            // pie.setLegendPosition('right');
            pie.draw();
        });
    </script>
@endif