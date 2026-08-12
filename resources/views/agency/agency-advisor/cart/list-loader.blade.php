<table class="table-pending-grants">
    <thead>
        <tr>
            <th style="text-align: left !important;">Fund Name</th>
            <th style="text-align: left !important;">Organization Name</th>
            <th style="text-align: left !important;">Amount ($)</th>
            <th style="text-align: left !important;">From</th>
            <th style="text-align: left !important;">Added On</th>
            <th style="text-align: left !important;">Action</th>
        </tr>
    </thead>
    <tbody id="ajax-data"></tbody>   
</table>

<div class="ajax-data-loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px"> Loading funds...
</div>

<script type="text/javascript">

    class AdvisorCartListLoader
    {
        constructor(){
            this.page         = 1;
            this.fund_id      = 0;
            this.ajaxMoreData = 1;
            this.ajaxLoading  = false;
            this.baseURL      = '/';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?';
            this.ui_data_loading =  $('.ajax-data-loading');
        }

        getfundId(fund_id){
           this.fund_id   = fund_id;
        }

        runLoadData(){
            if (jsAdvisorCartListLoader.ajaxMoreData !== 1) return;
            if (this.ajaxLoading == true) return;
            this.ajaxLoading = true;
            //this.loadData(this.baseURL + 'page=' + this.page+ '&fund_id=' + this.fund_id);
            this.loadData(this.baseURL + 'page=' + this.page);
        }

        loadData(url){
            console.log("loadData : ", url);

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
                    if (_this.page == 1) {
                        _this.ui_data_loading.html("Data not found");
                    } else {
                        _this.ui_data_loading.html("");
                    }
                    _this.ajaxMoreData = 0;
                    // _this.page++;
                    return;
                }
                console.log('data: 2');
                _this.ui_data_loading.hide();

                var pageId = "funds-page-" + _this.page;
                $("#ajax-data").append(data.html);
                $("#" + pageId).fadeIn(600);
                _this.page++;
                jsAdvisorCartListLoader.runLoadData();

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                alert('Some error occurred while processing your request!');
            });
        }
    }

    var jsAdvisorCartListLoader = new AdvisorCartListLoader();
</script>
