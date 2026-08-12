

class PieChart {

    constructor() {
        console.log('PieChart');
        this.chartData = [];
        this.chartLabel = [];
        this.chartColor = [];
        this.pieChartOptions = {};
        this.pieChartData = {};
    }

    // defaults
    dynamicColors() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    init(data, id, options=[]) {
        var _this = this;
        // set data
        // var theChartData = <?=json_encode($chartData)?>;
        this.pieChartView = document.getElementById(id);

        $.each(data, function(key, obj) {
            _this.chartData.push(obj.value);
            _this.chartLabel.push(obj.label);
            _this.chartColor.push(_this.dynamicColors());
        });
        this.pieChartData = {
            datasets: [{
                data: this.chartData,
                backgroundColor: this.chartColor,
                borderColor: 'rgba(200, 200, 200, 0.75)',
                hoverBorderColor: 'rgba(200, 200, 200, 1)'
            }],
            labels: this.chartLabel
        };
    }

    setOptions(options=[]) {
        // set options
        this.pieChartOptions = {
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

                        var dataset = data['datasets'][0];
                        var dataMeta = dataset['_meta'];
                        var totalAmount = '';
                        for (var key in dataMeta) {
                            if (dataMeta.hasOwnProperty(key)) {
                                totalAmount = parseFloat(dataMeta[key]['total']);
                            }
                        }
                        if (totalAmount != '') {
                            var percent = Math.round((dataset['data'][tooltipItem['index']] / totalAmount) * 100)
                            return customLabel+' (' + percent + '%)';
                        } else {
                            return customLabel;
                        }
                    },
                    label2: function(tooltipItem, data) {
                        var labelValue = parseInt(data['datasets'][0]['data'][tooltipItem['index']]);
                        return labelValue.toLocaleString("en-US",{style:"currency", currency:"USD"});
                    },
                    afterLabel2: function(tooltipItem, data) {
                        var dataset = data['datasets'][0];
                        var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100);
                        return '(' + percent + '%)';
                    }
                },
                backgroundColor: '#FFF',
                titleFontSize: 14,
                titleFontColor: '#0066ff',
                bodyFontColor: '#000',
                bodyFontSize: 14,
                displayColors: true
            }
        };
    }

    draw() {
        // For a pie chart
        var myPieChart = new Chart(this.pieChartView, {
            type: 'pie',
            data: this.pieChartData,
            options: this.pieChartOptions
        });

        // added for jcf
        if (this.chartLabel.length < 10) {
            $('#id_show_more').hide();
        }
    }

}