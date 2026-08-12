<?php
$data = \App\Models\PerformanceData::getPerformance($accountId, $accountType);
$barChartsData = isset($data['barChartsData']) ? $data['barChartsData'] : [];
$barChartsTable = isset($data['barChartsTable']) ? $data['barChartsTable'] : [];
?>

<div class="chart-box">
    <h3 class="page-title">Performance Summary</h3>

    @if(isset($poolName))
	    <h4 class="rpane-subtitle">{{$poolName}}</h4>
    @endif

    <div class="tabs_oval" id="id_tabs_fund_performance">
        <ul class="nav nav-tabs">
            @foreach($barChartsData as $i => $data)
                <li class="nav-item"><a class="nav-link  {{$i == 0 ? 'active' : ''}}" href="#iid_gd_bar_chart_{{$i}}" data-toggle="tab">{{$barChartsData[$i]['title']}}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($barChartsData as $i => $data)
                <div class="tab-pane {{$i == 0 ? 'active' : 'manage-display'}}" id="iid_gd_bar_chart_{{$i}}">
                    <div class="chart-container chart">
                        <canvas id="id_gd_bar_chart_{{$i}}" width="400" height="400"></canvas>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <br/>

    <div style="overflow: auto">
        <table class="summary-table">
            @foreach($barChartsTable as $i => $row)
                @if($i==0)
                    <tr>
                        @foreach($row as $j => $item)
                            @if($j)
                                <th>{{$item}}</th>
                            @endif
                        @endforeach
                    </tr>
                @else
                    <tr class="{{$row[0]}}">
                        @foreach($row as $j => $item)
                            @if($j == 0)
                            @elseif($j == 1)
                                <td>{{$item}}</td>
                            @else
                                <td>{{number_format((float)$item, 2, '.', '')}}</td>
                            @endif
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </table>
    </div>

</div>

<div class="gh_data_source" style="text-align: right">
    <hr style="margin-bottom: 4px">
    <small>Data Source: <a href="https://www.ghill.com/" target="_blank">GreenHill</a></small>
</div>

<script>
    $(function(){
        var gssData = <?=json_encode($barChartsData)?>;
        console.log("gssData: ", gssData);

        gssData.forEach(function(value, index){
            console.log("index: " + index);

            var gsData = gssData[index];
            console.log("gsData-" + index + ": ", gsData);
            var options = {};
            options.scales = '$';
            var bar = new BarChart();
            bar.init(gsData, 'id_gd_bar_chart_' + index);
            bar.setOptions(options);
            // bar.setScales('$');
            // bar.setLegendPosition('right');
            bar.draw();
        });

    });

    $(function(){
        $('.manage-display').removeClass('manage-display');
    });

</script>
