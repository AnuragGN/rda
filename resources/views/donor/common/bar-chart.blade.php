<?php
$borderWidth = 0.5;
if (\App\Models\ClientInfo::isJCF()) {
    //$colors[0] = '#125294'; // dark blue
    //$colors[1] = '#00929F'; // teal
    //$colors[2] = '#F47521'; // saffron
    //$colors[3] = '#FFC753'; // yellowish
    //$colors[4] = '#65696E'; // dark grey
    //$colors[5] = '#B32317'; // brown
    //$color = $colors[rand (0, 5)];
    $color = '#195392';
} else if (\App\Models\ClientInfo::isGNA()) {
    $color = '#125294';
} else if (\App\Models\ClientInfo::isHGA()) {
    $color = '#0093b2';
    $borderWidth = 0;
} else {
    $color = 'red';
}

?>
@if (count($chartData))

    <div class="bar-chart-container chart">
        <canvas id="barChart" width="300" height="200"></canvas>
    </div>

    <script>
        var theChartData = <?=json_encode($chartData)?>;
        var theBgColor = '<?=$color?>';
        var chartLabel = '<?=$label?>';
        var borderWidth = '<?=$borderWidth?>';

        var barChartData = {
            labels: theChartData.label,
            datasets: [{
                label: chartLabel,
                backgroundColor: theBgColor,
                borderColor: 'black',
                borderWidth: borderWidth,
                data: theChartData.data
            }]
        };

        window.onload = function() {
            var ctx = document.getElementById('barChart').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Monthly Grants Bar Chart'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toLocaleString("en-US",{style:"currency", currency:"USD"});
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            title: function(tooltipItem, data) {
                              return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function(tooltipItem, data) {
                                var labelValue = parseInt(data['datasets'][0]['data'][tooltipItem['index']]);
                                var customLabel = labelValue.toLocaleString("en-US",{style:"currency", currency:"USD"});
                                return customLabel;
                            }
                        }
                    }
                }
            });
        };
    </script>

@endif
