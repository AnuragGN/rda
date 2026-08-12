@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->MAKE_A_GIFT])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-9">

                    <div class="row">
                        <div class="col-sm-11">
                            <p class="fw300" s>Fill the form below and click on Continue button to provide gift details (your credit/debit card or bank details).</p>
                        </div>
                    </div>

                    <div class="form-make-grant gn-form">
                        {{-- {!! Form::open( ['action' => 'FundController@saveGrant', 'files' => false, 'id' => 'content-form' ]) !!}--}}
                        {{-- route is not used --}}
                        {!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['add-to-cart'], 'id' => 'credit-fund-form']) !!}
                        <div class="row">
                            <div id='id_change_form_layout' class="col-sm-11">
                                @if($custom->feature->STRIPE_PAYMENT)
                                    @include('stripe._form')
                                @else
                                    @include('donor.transactions.gift._form')
                                @endif
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @if($custom->feature->STRIPE_PAYMENT)
                            @include('stripe.auth-stripe-form')
                        @else
                            @include('donor.transactions.auth-net-form')
                        @endif
                    </div>

                </div>

                <div class="col-xl-3">
                    @include(\App\Models\ClientInfo::clientViewFor("transactions.gift._right_pane", "donor."))
                </div>

            </div>
        </div>
    </div>

@endsection
