@php
$maxStocks = \App\Models\ClientConfig::value('DAF_MAX_CONTRIBUTION_STOCKS');
@endphp
@extends ('donor.registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Contributions'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <p class="form-title">Stock Certificates Held in Personal Possession</p>
                    </div>
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    @foreach($stocks as $i => $stock)

                        @if($stock->isNew && count($stocks) > 1)

                            @if(count($stocks) <= $maxStocks)
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0);" id="id_add_stock_btn" onclick="addStock();">
                                            <i class="fas fa-plus-circle"></i> Add more</a>
                                    </div>
                                </div>
                            @endif

                            <div id="id_add_stock" style="display: none" class="daf-form-card" >

                                @include("donor.registration._form_stocks")

                            </div>
                        @else
                            <div id="{{'id_stock_' . $stock->key}}" class="daf-form-card" >

                                @include("donor.registration._form_stocks")

                            </div>
                        @endif
                        <br>
                    @endforeach
                </div>

                @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-contributions", "donor."))

            </div>
        </div>
    </div>

    <script>
        function addStock() {
            $('#id_add_stock').show(600);
            $('#id_add_stock_btn').fadeOut();
        }

        function deleteStock(e) {

            var key = $(e).attr('data');
            var body = $("body");

            var message = "<div style='text-align: center'>Are you sure you want to delete this item?</div><hr class='mb-0'>";

            $.confirm({
                columnClass: 'medium',
                title: '',
                content: message,
                buttons: {
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-light',
                        keys: ['enter', 'shift'],
                        action: function () {
                        }
                    },
                    yes: {
                        text: 'Delete',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function () {
                            body.css("cursor", "progress");
                            body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                            window.location.href = "{{route("delete-contributions-stock", $id)}}?key="+key;
                        }
                    }
                }
            });
            return false;
        };
    </script>


@endsection

