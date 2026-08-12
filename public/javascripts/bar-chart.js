

class BarChart {

    constructor() {
        console.log('BarChart');
        this.title = null;
        this.barChartData = {};
        this.barChartOptions = {};
    }

    // defaults
    static dynamicColors() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    init(data, id) {
        this.barChartData = data;
        this.barChartView = document.getElementById(id).getContext('2d');
        console.log("this.barChartData: ", this.barChartData);
    }

    setOptions(options={}) {
        // set options
        this.barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            title: {
                display: false,
                text: 'Monthly Grants Bar Chart'
            },
            scales: {}
        };

        if (this.title != null && this.title.length > 0) {
            this.barChartOptions.title.display = true;
            this.barChartOptions.title.text = this.title;
        }
    }
    setLegendPosition(value) {
        this.barChartOptions.legend.position = value;
    }
    setScales(value) {
        if (value == '$') {
            this.barChartOptions.scales = {
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value.toLocaleString("en-US",{style:"currency", currency:"USD"});
                        }
                    }
                }]
            };
        }
    }

    draw() {
        // For a bar chart
        var myBarChart = new Chart(this.barChartView, {
            type: 'bar',
            data: this.barChartData,
            options: this.barChartOptions
        });
    }

}