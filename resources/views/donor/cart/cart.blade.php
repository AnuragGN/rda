@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\GrantForm::cartLabel() . ' (' . count($models) . ')'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <h1 class="page-title two-column w100 mt-2">
                            <span></span>
                            <a href="{{ route('grant-create') }}" class="btn btn-accent btn-sm">
                                {{ \App\Models\ClientInfo::isHGA() ? "Make Another Grant Recommendation" : "Make Another Grant" }}
                            </a>
                        </h1>

                        {!! Form::open(array('route' => ['checkout', 0], 'id' => 'id_form_checkout')) !!}

                        @include('donor.cart.list')

                        @if(count($models))
                            <div class="row">
                                <div class="col-12">
                                    <div class="cart-grant-footer">
                        <span class="text-primary-dark">Total Recommended:
                            <span class="fw700 text-accent" id="js_grant_total">$0</span>
                        </span>

                                        {{-- for Marjory Kaplan Fund --}}
                                        @include('donor.cart.mkf_input')

                                        {!! Form::submit('Proceed to Checkout', ['name' => 'action', 'id'=> 'js_confirm_grants', 'class' => 'btn btn-accent']) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var models = @json($models);
        var gGrantsTotal = 0;
        var gGrantsClosing = false;

        function cartUpdateTotal() {
            gGrantsTotal = 0;
            gGrantsClosing = false;
            models.forEach(function (model, index) {
                var element = $('#cb_' + model.cart_id);
                var isChecked = element.prop("checked");
                if (isChecked) {
                    if (model.amount && model.amount != 0) {
                        gGrantsTotal += parseFloat(model.amount);
                        console.log( '' + model.cart_id + ' is checked : ' + model.amount);
                    } else {
                        if (model.is_closing_grant) {
                            gGrantsClosing = true;
                            console.log( '' + model.cart_id + ' is closing : ' + model.is_closing_grant);
                        }
                    }
                } else {
                    // console.log( '' + model.cart_id + ' is not checked : ' + model.amount);
                }
            });
            $("#js_grant_total").html( '$' + floatWithCommas(gGrantsTotal));

            // TODO:
            // if (gGrantsTotal == 0) {
            //    $("#js_confirm_grants").addClass('disabled');
            // } else {
            //    $("#js_confirm_grants").removeClass('disabled');
            // }
        }

        $('.js_grant_cb').change(function() {
            cartUpdateTotal();
        });

        $(function(){
            cartUpdateTotal();
        });
    </script>

    @if(\App\Models\ClientInfo::isJCF())
        @include('jcf.cart.mag_prompt')
    @else
        <script>
            $('#js_confirm_grants').on('click',function(){
                // console.log( 'confirm : ' + gGrantsClosing);
                if (gGrantsClosing != true && gGrantsTotal == 0) {
                    alert("Please select one or more Grants to continue.");
                    return false;
                } else {
                    // alert("Grant total is :" + gGrantsTotal);
                    return true;
                }
            });
        </script>
    @endif

    <script>
        // confirmation
        $('.js_remove_grant_from_cart').on('click', function(){
            let item = this;
            $(item).closest(".cart-grant").addClass('opacity40');
            $.confirm({
                // title: 'Delete Recommendation?',
                title: '',
                content: 'Are you sure you want to delete this grant recommendation?',
                // icon: 'fa fa-exclamation-circle',
                animation: 'scale',
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-light',
                        keys: ['enter', 'shift'],
                        action: function(){
                            $(item).closest(".cart-grant").removeClass('opacity40');
                        }
                    },
                    yes: {
                        text: 'Delete',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function(){
                            removeGrantFromCart(item);
                        }
                    }
                }
            });
        });

        // fund statement fund-links
        //$('.js_remove_grant_from_cart').on('click', function () {
        function removeGrantFromCart(item) {
            var url = $(item).data('href');
            var parent = $(item).data('parent-id');
            // console.log("url=" + url);
            $("body").css("cursor", "progress");

            $.ajax({
                url: url,
                dataType: 'json',
                method: 'get'
            }).done(function (data) {
                if (!data || data.status != 200) {
                    if (data.mesg) showAlert('Error', data.mesg);
                    else showAlert('Error', "Your request could not be processed!");
                } else {

                    var itemId = $(item).closest(".cart-grant").data('item-id');
                    // console.log("Grant item Id: " + itemId);
                    $('#cb_' + itemId).prop("checked", false);
                    cartUpdateTotal();

                    // console.log("Remove: " + data['fund-id']);
                    $('#' + parent).toggle('slow', 'swing');
                }
                $("body").css("cursor", "default");
                $(item).closest(".cart-grant").removeClass('opacity40');
            }).fail(function(){
                alert("Ops! Some error!");
                $("body").css("cursor", "default");
                $(item).closest(".cart-grant").removeClass('opacity40');
            });
        }

    </script>

@endsection
