<div id="id_ajax_pending_grants"></div>
<div class="id_ajax_pending_grants_loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px">Checking pending grants...
</div>

<script type="text/javascript">

    class PendingGrantsLoader
    {
        constructor(){
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
            this.baseURL = '/';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?';
            this.ui_data_loading =  $('.id_ajax_pending_grants_loading');
        }

        runLoadData(){
            if (jsFundListLoader.ajaxMoreData !== 1) return;
            if (this.ajaxLoading == true) return;
            this.ajaxLoading = true;
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
                // console.log('data: ' + JSON.stringify(data));

                _this.ajaxMoreData = data.more;
                _this.ajaxLoading = false;
                if (data.html == undefined || data.html == "") {
                    console.log('data: 1');
                    if (_this.ajaxMoreData !== 1) {
                        _this.ui_data_loading.html("");
                    } else {
                        _this.page++;
                        _this.runLoadData();
                    }
                    return;
                }
                console.log('data: 2');
                _this.ui_data_loading.hide();

                var pageId = "grants-page-" + _this.page;
                var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
                var div = $(pageHtml).hide();
                $("#id_ajax_pending_grants").append(div);
                $("#" + pageId).fadeIn(600);
                _this.page++;
                _this.runLoadData();

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                alert('Some error occurred while processing your request!');
            });
        }
    }

    var jsPendingGrantsLoader = new PendingGrantsLoader();

</script>
