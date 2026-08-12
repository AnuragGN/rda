<div id="ajax-data"></div>
<div class="ajax-data-loading" style="opacity: 0.5; color: #646464">
    <img src="/ma/images/spinner.gif" width="16px"> Loading funds...
</div>

<script type="text/javascript">

    class FundListLoader
    {
        constructor(){
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxLoading = false;
            this.baseURL = '/';
        }

        init(baseUrl){
            this.baseURL = baseUrl + '?'; // '/charitable-catalog/orgs/ajax?';
            this.ui_data_loading =  $('.ajax-data-loading');
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
                var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
                var div = $(pageHtml).hide();
                $("#ajax-data").append(div);
                $("#" + pageId).fadeIn(600);
                _this.page++;
                jsFundListLoader.runLoadData();

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ui_data_loading.hide();
                _this.ajaxLoading = false;
                alert('Some error occurred while processing your request!');
            });
        }
    }

    var jsFundListLoader = new FundListLoader();

//    $(function(){
//        $(window).scroll(function () {
//            if ($(window).scrollTop() + $(window).height() + 200 >= $(document).height()) {
//                if (jsFundListLoader.ajaxMoreData == 0) return;
//                jsFundListLoader.runLoadData();
//            }
//        });
//    });

</script>
