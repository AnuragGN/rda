<?php
$borderWidth = 0.5;
if (\App\Models\ClientInfo::isJCF()) {
    $color = '#195392';
} else if (\App\Models\ClientInfo::isGNA()) {
    $color = '#125294';
} else if (\App\Models\ClientInfo::isHGA()) {
    $color = '#0093b2';
    $borderWidth = 0;
} else {
    $color = 'blue';
}
?>
@if (count($contributionChartData))

    <div class="bar-chart-container chart">
        <canvas id="barChartContribution" width="300" height="200"></canvas>
    </div>
    <script>
        var contributionChartData = <?php echo json_encode($contributionChartData)?>;
        var BgColor = '<?php echo $color?>';
        var borderWidth = '<?php echo $borderWidth?>';

        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('barChartContribution').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: {  
                    labels: contributionChartData.label,
                    datasets: [{
                        label: 'Contributions (Last 300 days)',
                        data: contributionChartData.data,
                        backgroundColor:theBgColor,
                        borderColor: 'red',
                        borderWidth: borderWidth,
                    }]
                },
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
                                min:0,
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
        });
    </script>
@endif
