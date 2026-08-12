
<div id="ajax-data"></div>
<div class="ajax-data-loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px"> Loading funds...
</div>

<script type="text/javascript">

    class AdvisorCartDetailLoader
    {
        constructor(){
            this.cart_id = 0;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
            this.baseURL = '/';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?';
            this.ui_data_loading =  $('.ajax-data-loading');
        }

        getCartId(cart_id){
            this.cart_id = cart_id;
        }
        runLoadData(){
            if (jsAdvisorCartDetailLoader.ajaxMoreData !== 1) return;
            if (this.ajaxLoading == true) return;
            this.ajaxLoading = true;
            this.loadData(this.baseURL + 'cart_id='+this.cart_id);
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

                $("#ajax-data").append(data.html);
                jsAdvisorCartDetailLoader.runLoadData();

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                alert('Some error occurred while processing your request!');
            });
        }
    }

    var jsAdvisorCartDetailLoader = new AdvisorCartDetailLoader();
</script>
