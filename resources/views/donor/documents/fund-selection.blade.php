@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->FUND_STATEMENTS])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8">
                    @if(\App\Models\ClientInfo::isHGA())
                        <p>Select a Donor-Advised Fund to review past/historical statements.</p>
                        @else
                        <p>Select a fund to view available statements.</p>
                        @endif
                    <div class="form-make-grant gn-form">

                        {!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['add-to-cart'], 'id' => 'grant-form']) !!}
                        <div class="row">
                            <div id='id_change_form_layout' class="col-sm-11">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        {!! Form::select('fund_id', $funds, null, ['id' => 'id_fund_id', 'class' => 'form-control', 'onchange'=> "onChangeFund(this)"]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="ajax-data-loading" style="display: none">Loading...</div>
                        <div id="ajax-data"></div>

                    </div>

                </div>
                <div class="col-lg-4">
                    @include('donor.documents.my-documents-menu')
                </div>
            </div>
            <br><br><br>
        </div>
    </div>

    <script>
        $(function(){
            var fundId = '<?php echo $fundId; ?>';
            onFundSelection(fundId);
        });

        function onChangeFund(item) {
            var fundId = $(item).val();
            onFundSelection(fundId);
        }

        function onFundSelection(fundId) {
            var url = '/m/my-statements/' + fundId + '/ajax';
            var ajaxLoading = $('.ajax-data-loading');
            var ajaxData = $('#ajax-data');
            ajaxData.html("");

            if (fundId == null || fundId == '') {
                return;
            }

            $.ajax({
                url: url,
                type: 'get',
                beforeSend: function () {
                    ajaxData.show();
                }
            }).done(function (data) {
                if (data.html == undefined || data.html == "") {
                    ajaxData.html("No data found.");
                    return;
                }

                var pageId = "page";
                var pageHtml = '<div id="' + pageId + '">' + data.html + '</div>';
                var div = $(pageHtml).hide();
                ajaxData.append(div);
                $("#" + pageId).fadeIn(400);

            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('Your request could not be completed. Please retry later..');
            });
        }
    </script>

@endsection
