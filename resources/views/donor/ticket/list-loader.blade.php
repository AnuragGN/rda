<table class="table-pending-grants">
    <thead>   
        <tr>
            <th style="text-align: left !important;">Ticket No.</th>
            <th style="text-align: left !important;">Subject</th>
            <th style="text-align: left !important;">Assigned To</th>
            <th style="text-align: left !important;">Created At</th>
            <th style="text-align: left !important;">Ticket Detail</th>
            <th style="text-align: left !important;"></th>
        </tr>
    </thead>
    <tbody id="ajax-data"></tbody>   
</table>
<div class="ajax-data-loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px"> Loading funds...
</div>

<script type="text/javascript">

    class TicketLoader
    {
        constructor(){
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
            this.baseURL = '/';
            this.ticket_search = '';
            this.status = '0';
            this.priority = '0';
            this.category = '0';
            this.flag = '';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?';
            this.ui_data_loading =  $('.ajax-data-loading');
        }

        runFilterData(ticket_search, status,priority,category){
            
            this.ticket_search = ticket_search;
            this.status = status;
            this.priority = priority;
            this.category = category;
            this.flag = 1;
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
        }
        runLoadData(){
            
            if (jsTicketLoader.ajaxMoreData !== 1) return;
            if (this.ajaxLoading == true) return;
            this.ajaxLoading = true;
            this.pageURL = this.baseURL + 'page=' + this.page+ '&ticket_search=' + this.ticket_search+ '&status=' + this.status+ '&priority=' +this.priority+'&category='+this.category;
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
                    console.log('data: 1');
                    $("#ajax-data").html("Data not found");
                    
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
                    $("#ajax-data").html(data.html);
                }else{
                  $("#ajax-data").append(data.html);  
                }
                _this.flag = '';
                $("#" + pageId).fadeIn(600);
                _this.page++;
                jsTicketLoader.runLoadData();
                ticketGraph();

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                this.flag = 0;
                alert('Some error occurred while processing your request!');
            });
        }
    }
    
    var jsTicketLoader = new TicketLoader();

function ticketGraph()
{
    var total_open = 0;
    var total_inprogress = 0;
    var total_hold = 0;
    var total_close = 0;
    $('.ticket-class').each(function(index, element) {
         
        if($(element).val() == 'open'){
            total_open++;
        }
        if($(element).val() == 'in progress'){
            total_inprogress++;
        }
        if($(element).val() == 'hold'){
            total_hold++;
        }
        if($(element).val() == 'closed'){
            total_close++;
        }
    });

    var arr = {'Open': total_open,'In Progress': total_inprogress,'Hold': total_hold,'Closed': total_close};

    var dynamicDataPoints = [];
    $.each(arr, function(key, value) 
    {
        dynamicDataPoints.push({
            label: key,
            y: value
        });
    });
    console.log(dynamicDataPoints);
    // Display Graph
    var options = {
        animationEnabled: true,
        title: {
            text: '',
            fontFamily: "Arial", 
            fontSize: 18,
        },
        data: [{
            type: "doughnut",
            innerRadius: "30%",
            showInLegend: true,
            legendText: "{label}",
            indexLabel: "{label}: {y}",
            dataPoints: dynamicDataPoints
        }]
    };
    $("#chartContainer").CanvasJSChart(options);
    // End
}
</script>

