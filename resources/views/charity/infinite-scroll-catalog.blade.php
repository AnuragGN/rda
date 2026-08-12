<div id="ajax-data"></div>
<div class="ajax-data-loading"><img src="/ma/images/spinner.gif" width="16px"> Loading...</div>

<script type="text/javascript">
    // var gid = < ? = gid ? >;
    var query = "{{$query}}";

    class JsCatalog
    {
        constructor(){
            this.page = 1;
            this.ajaxMoreData = 1;
            this.ajaxMoreLoading = false;
        }

        init(){
            this.ajax_data_loading =  $('.ajax-data-loading');
            // this.baseURL = '/stream/group/' + gid;
            this.baseURL = '/m/catalog/orgs/ajax?';
            if (query){
                this.baseURL = this.baseURL + query + '&';
            }
        }

        runLoadData() {
            if (this.ajaxMoreLoading == true) return;
            this.ajaxMoreLoading = true;
            this.loadData(this.baseURL + 'page=' + this.page);
        }

        loadData(url) {
            console.log("loadData : ", url);

            var _this = this;
            $.ajax({
                url: url,
                type: 'get',
                beforeSend: function () {
                    $('.ajax-data-loading').show();
                }
            }).done(function (data) {
                // console.log('data: ' + JSON.stringify(data));

                _this.ajaxMoreData = data.more;
                _this.ajaxMoreLoading = false;
                if (data.html == undefined || data.html == "") {
                    console.log('data: 1');
                    _this.ajax_data_loading.html("No more records found");
                    _this.ajaxMoreData = 0;
                    this.page++;
                    return;
                }
                console.log('data: 2');
                _this.ajax_data_loading.hide();

                var pageId = "page-" + _this.page;
                var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
                var div = $(pageHtml).hide();
                $("#ajax-data").append(div);
                $("#" + pageId).fadeIn(400);
                _this.page++;

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                _this.ajaxMoreLoading = false;
                alert('server not responding...');
            });
        }
    }

    var jsCatalog = new JsCatalog();
    $(function(){
        jsCatalog.init();
        jsCatalog.runLoadData();

        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() + 200 >= $(document).height()) {
                if (jsCatalog.ajaxMoreData == 0) return;
                jsCatalog.runLoadData();
            }
        });

    });

</script>
