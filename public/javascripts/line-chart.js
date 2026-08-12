

class LineChart {

    constructor() {
        console.log('LineChart');
        this.title = null;
        this.lineChartData = {};
        this.lineChartOptions = {};
    }

    // defaults
    static dynamicColors() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    init(data, id) {
        this.lineChartData = data;
        this.lineChartView = document.getElementById(id).getContext('2d');
        console.log("this.lineChartData: ", this.lineChartData);
    }

    setOptions(options={}) {
        // set options
        this.lineChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            title: {
                display: false,
                text: ''
            },
            elements: {
                line: {
                    tension: 0
                }
            },
            scales: {},
            showLine: true,
            bezierCurve: false,
            fill: false,
            //lineTension: 0
        };

        if (options.scales == '$') {
            var scales = {
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value.toLocaleString("en-US",{style:"currency", currency:"USD"});
                        }
                    }
                }]
            };
            this.lineChartOptions.scales = scales;
        }

        if (this.title != null && this.title.length > 0) {
            this.lineChartOptions.title.display = true;
            this.lineChartOptions.title.text = this.title;
        }
    }
    setLegendPosition(value) {
        this.lineChartOptions.legend.position = value;
    }

    draw() {
        // For a line chart
        var myLineChart = new Chart(this.lineChartView, {
            type: 'line',
            data: this.lineChartData,
            options: this.lineChartOptions
        });
    }

}