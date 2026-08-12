<?php

// Format Pie chart data
$pieChartData = [];

foreach($groups as $group) {
    if ($group['type'] == 'group' && $group['title'] == 'FUND BALANCE') {
        foreach ($group['items'] as $item) {
            if ($item['type'] == 'pool' && count($item['children'])) {
                $chartData = [];
                $chartData['name'] = $item['name'];
                $chartData['key'] = $item['key'];
                $data = [];
                foreach ($item['children'] as $children) {
                    if (isset($children['amount_raw'])) {
                        $amount = $children['amount_raw'];
                    } else {
                        $amount = preg_replace("/([^0-9\\.])/i", "", $children['amount']);
                    }

                    $childrenData = [];
                    $label = $children['link'].':'.$children['name'];
                    $childrenData['label'] = mb_strimwidth($label, 0, 40, '...');
                    $childrenData['value'] = $amount;
                    $data[] = $childrenData;
                }
                $chartData['data'] = $data;
                $pieChartData[] = $chartData;
            }
        }
    }
}

?>

@foreach($pieChartData as $i => $pieChart)

    <div class="chart-box">
        <div class="title">{{ $pieChart['name'] }}</div>
        <div class="chart-container chart" style="height: {{300+count($pieChart['data'])*22}}px">
            <canvas id="id_fs_pie_chart_{{$i}}" width="300" height="600"></canvas>
        </div>
    </div>

    <script>
        $(function(){
            var gsData = <?=json_encode($pieChart['data'])?>;
            var pie = new PieChart();
            pie.init(gsData, 'id_fs_pie_chart_{{$i}}');
            pie.setOptions();
            pie.draw();
        });
    </script>

@endforeach
