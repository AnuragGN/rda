
@if(count($chartData))

    <div class="chart-container chart" style="height: {{300+count($chartData)*22}}px">
        <canvas id="pieChart" width="300" height="600"></canvas>
    </div>

    <script>
        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        var theChartData = <?=json_encode($chartData)?>;

        var chartLabel = [];
        var setColor = [];
        var chartData = [];

        $.each(theChartData, function(key, obj) {
            chartData.push(obj.value);
            chartLabel.push(obj.label);
            setColor.push(dynamicColors());
        });

        var optionsPie = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                align: 'start'
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
                    },
                    afterLabel: function(tooltipItem, data) {
                      var dataset = data['datasets'][0];
                      var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
                      return '(' + percent + '%)';
                    }
                },
                backgroundColor: '#FFF',
                titleFontSize: 16,
                titleFontColor: '#0066ff',
                bodyFontColor: '#000',
                bodyFontSize: 14,
                displayColors: true
            }
        };

        var ctx = document.getElementById('pieChart');
        var data = {
            datasets: [{
                backgroundColor: setColor,
                borderColor: 'rgba(200, 200, 200, 0.75)',
                hoverBorderColor: 'rgba(200, 200, 200, 1)',
                data: chartData
            }],
            labels: chartLabel
        };

        // For a pie chart
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: optionsPie
        });

    </script>

@endif
