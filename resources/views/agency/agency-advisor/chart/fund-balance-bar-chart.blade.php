<?php
$borderWidth = 0.5;
if (\App\Models\ClientInfo::isJCF()) {
    $color1 = '#195392';
} else if (\App\Models\ClientInfo::isGNA()) {
    $color = '#125294';
} else if (\App\Models\ClientInfo::isHGA()) {
    $color = '#0093b2';
    $borderWidth = 0;
} else {
    $color = 'red';
}
?>
@if (count($fundsBalanceChartData))

    <div class="bar-chart-container chart">
        <canvas id="barChart" width="300" height="200"></canvas>
    </div>

    <script>
        var fundBalanceChartData = <?php echo json_encode($fundsBalanceChartData)?>;
        var theBgColor = '<?php echo $color?>';
        var borderWidth = '<?php echo $borderWidth?>';
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('barChart').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: fundBalanceChartData.label,
                    datasets: [{
                        label: 'Fund Balances',
                        data: fundBalanceChartData.data,
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
