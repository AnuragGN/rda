<div id="recommendation-ajax-data"></div>
<div class="ajax-data-loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px"> Loading funds...
</div>

<script type="text/javascript">

    class RecommendationListLoader
    {
        constructor(){
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
            this.baseURL = '/';
            this.fund_id = '';
            this.grant_date_range = '';
            this.start_date = '0';
            this.end_date = '0';
            this.flag = '';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?';
            this.ui_data_loading =  $('.ajax-data-loading');
        }

        runFilterData(fund_id,grant_date_range,start_date,end_date){
            
            this.fund_id = fund_id;
            this.grant_date_range = grant_date_range;
            this.start_date = start_date;
            this.end_date = end_date;
            this.flag = 1;
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
        }
        runLoadData(){
            
            if (jsRecommendationListLoader.ajaxMoreData !== 1) return;
            if (this.ajaxLoading == true) return;
            this.ajaxLoading = true;
            this.pageURL = this.baseURL + 'page=' + this.page+ '&fund_id=' + this.fund_id+ '&grant_date_range=' + this.grant_date_range+ '&start_date=' +this.start_date+'&end_date='+this.end_date;
            this.loadData(this.pageURL);
        }

        loadData(url){
            
            var _this = this;
            $.ajax({
                url: url,
                type: 'get',
                beforeSend: function () {
                    _this.ui_data_loading.show();
                }
            }).done(function (data) {
                
                 _this.ajaxMoreData = data.more;
                _this.ajaxLoading = false;

                if (data.html == undefined || data.html == "") {
                    
                    $("#recommendation-ajax-data").html("Data not found");
                    
                    if (_this.page == 1) {
                        _this.ui_data_loading.html("Data not found");
                    } else {
                        _this.ui_data_loading.html("");
                    }
                    _this.ajaxMoreData = 0;
                    this.flag = 0;
                    // _this.page++;
                    return;
                }
                
                _this.ui_data_loading.hide();

                var pageId = "funds-page-" + _this.page;
                var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
                var div = $(pageHtml).hide();
                
                if(_this.flag == 1)
                {
                    $("#recommendation-ajax-data").html(data.html);
                }else{
                  $("#recommendation-ajax-data").append(data.html);  
                }
                _this.flag = '';
                $("#" + pageId).fadeIn(600);
                _this.page++;
               jsRecommendationListLoader.runLoadData();
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                this.flag = 0;
                alert('Some error occurred while processing your request!');
            });
        }
    }
    var jsRecommendationListLoader = new RecommendationListLoader();

function recommendationGraph(fund_id,grant_date_range,start_date,end_date) 
{
    $.ajax({

        type: 'GET',
        url: "{{ route('agency-recommendation-graph-ajax') }}",
        data: { "fund_id":fund_id,'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
        dataType: 'json',
        success: function (data) {

            var dynamicDataPoints = [];
            for (var j in data) {

                var recomm_data = data[j];
                var total_amount     = recomm_data.amount;
                var name   = recomm_data.name;

                 // Code for Graph
                dynamicDataPoints.push({
                    label: name,
                    y: total_amount
                });
            }
            // Display Graph

            var display_label = '';

            var options = {
                animationEnabled: true,
                title: {
                    text: display_label,
                    fontFamily: "Arial", 
                    fontSize: 18,
                },
                axisY: {
                    labelFormatter: function (e) {
                        return '$' + e.value.toLocaleString('en-US');
                    }
                },
                data: [{
                    type: "doughnut",
                    innerRadius: "30%",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabel: "{label}: ${y}",
                    dataPoints: dynamicDataPoints
                }]
            };
            $("#chartContainer").CanvasJSChart(options);
            // End 
        }
    });
}
</script>
