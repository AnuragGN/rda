@extends ('donor.layouts.main')

@section ('content')

    {{-- dont't include this css in main-head, due to conflict in body {} --}}
    {{-- Stripe Checkout CSS--}}
    <link href="/ma/css/checkout.css" rel="stylesheet">

    @include('common.page-header', ['pageTitle' => $custom->text->MAKE_A_GIFT])

    <div class="container">
        <div class="form-wrapper">

            <div class="row">
                <div class="col-sm-11">
                    <p class="fw300">Fill the form below and click on Pay now to complete the payment.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <!-- Display a payment form -->
                    <form id="payment-form">
                        <div id="payment-element">
                            <!--Stripe.js injects the Payment Element-->
                        </div>
                        <button id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay now</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    {{--<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
    <script>
        // publishable API KEY
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const clientSecret = "{{ $clientSecret }}";
        const returnUrl  = "{{ route('payment-status') }}";
        const returnErrorStatusUrl  = "{{ route('on-payment-error') }}";
    </script>
    <script src="/javascripts/checkout.js"></script>

@endsection