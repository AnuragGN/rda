<div id="ajax-data"></div>
<div class="ajax-data-loading">Loading...</div>

<script type="text/javascript">
    var page = 1;
    var ajaxMoreData = 1;
    var ajaxMoreLoading = false;

    runLoadData();

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() + 400 >= $(document).height()) {
            if (ajaxMoreData == 0) return;
            runLoadData();
        }
    });

    function runLoadData() {
        if (ajaxMoreLoading == true) return;
        ajaxMoreLoading = true;
        var url = window.location.href;
        console.log("Full url: " + url);

        if (url.indexOf('?') > -1) {
            loadData(url + '&page=' + page, page);
        } else {
            loadData(url + '?page=' + page, page);
        }

        page++;
    }

    function loadData(url, page) {
        console.log("loadData : ", url);
        $.ajax({
            url: url,
            type: 'get',
            beforeSend: function () {
                $('.ajax-data-loading').show();
            }
        }).done(function (data) {
            ajaxMoreData = data.more;
            ajaxMoreLoading = false;
            if (data.html == undefined || data.html == "") {
                $('.ajax-data-loading').html("No more records found");
                ajaxMoreData = 0;
                return;
            }
            $('.ajax-data-loading').hide();

            var pageId = "page-" + page;
            var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
            var div = $(pageHtml).hide();
            $("#ajax-data").append(div);
            $("#" + pageId).fadeIn(400);

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            ajaxMoreLoading = false;
            alert('server not responding...');
        });
    }
</script>
