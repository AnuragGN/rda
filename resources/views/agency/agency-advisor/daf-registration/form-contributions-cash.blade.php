@php
$ccPaymentDetails = isset($contributions->credit_card) ? $contributions->credit_card : null;
@endphp
@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Contributions'])
    <style>
        .blur-contribution-method {
            opacity: 0.7;
        }
    </style>
    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-md-8">

                    @if(\App\Models\ClientInfo::isPFR())
                        <p class="">How do you plan to fund your Donor-Advised Fund? You can fund your Donor-Advised Fund using cash equivalents on this screen or click Skip for gifts of Securities or Mutual Funds</p>
                    @endif

                    <div class="form-group">
                        <p class="form-title">
                            @if($custom->feature->STRIPE_PAYMENT)
                                Cash Equivalents - Credit Card
                            @else
                                Cash Equivalents - Credit Card/ACH
                            @endif
                            <a href="{{route($nextRedirectUrl, $id)}}" class="btn btn-sm btn-light col-sm-2" style="float: right">Skip</a>
                        </p>
                    </div>

                    @if($ccPaymentDetails)

                        <div class="form-group row">
                            <label class="offset-sm-1 col-sm-3 col-form-label text-right pr-0">Paid Amount ($)</label>
                            <div class="col-sm-3"> {{$model['amount']}} </div>
                            <label class="col-sm-1 col-form-label text-right pr-0">Date</label>
                            <div class="col-sm-3">
                                {{ \App\Helpers\GnUtils::customDate($model['transaction_date']) }}
                            </div>
                        </div>

                    @else

                        <form method="POST" action="{{ route('post-daf-contributions-cc', $id) }}" id="contributions_credit_card_form">
                            @csrf
                            @if($custom->feature->STRIPE_PAYMENT)
                                @include('agency.agency-advisor.transactions.daf-contributions.stripe._form')
                            @else
                                @include('agency.agency-advisor.transactions.daf-contributions._form')
                            @endif
                        </form>
                    @endif

                        @if($custom->feature->STRIPE_PAYMENT)
                            @include('agency.agency-advisor.transactions.daf-contributions.stripe.auth-stripe-form')
                        @else
                            @include('agency.agency-advisor.transactions.daf-contributions.daf-auth-net-form')
                        @endif
                    <br>
                    <div class="form-group">
                        <p class="form-title">Cash Equivalents - Other</p>
                    </div>
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>
                    <form method="POST" action="{{ route('post-agency-daf-contributions-cash', $id) }}" id="daf-account-info-form">
                        @csrf

                    {{-- 1. CHECK --}}
                    <div class="form-group check-details row">
                        <div class="col-md-2">
                            <input type="checkbox" name="check_pay" id="id_check_pay" value="1"
                                   class="form-check-label pt-0" onchange="onCheckToggle(this)"
                                   {{ old('check_pay', $contributions->check_pay ?? false) ? 'checked' : '' }}>
                            CHECK
                        </div>
                        <label for="id_check_amount" class="col-md-2 col-form-label text-right pr-0">Check<br> Amount ($)</label>
                        <div class="col-md-3">
                            <input type="text" name="check_amount" id="id_check_amount" class="form-control"
                                   minlength="1" maxlength="10" readonly
                                   value="{{ old('check_amount', $contributions->check_amount ?? '') }}">
                        </div>
                    </div>

                    {{-- 2. Wire --}}
                    <div class="wire-details">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <input type="checkbox" name="wire_pay" id="id_wire_pay" value="1"
                                       class="form-check-label pt-0" onchange="onWireToggle(this)"
                                       {{ old('wire_pay', $contributions->wire_pay ?? false) ? 'checked' : '' }}>
                                WIRE
                            </div>
                            <label for="id_wire_amount" class="col-md-2 col-form-label text-right pr-0">Wire<br> Amount ($)</label>
                            <div class="col-md-3">
                                <input type="text" name="wire_amount" id="id_wire_amount" class="form-control pr-0"
                                       minlength="1" maxlength="10" readonly
                                       value="{{ old('wire_amount', $contributions->wire_amount ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_wire_bank" class="offset-md-2 col-md-2 col-form-label text-right pr-0">Wire Bank Name</label>
                            <div class="col-md-5">
                                <input type="text" name="wire_bank" id="id_wire_bank" class="form-control"
                                       onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                                       minlength="3" maxlength="32" readonly
                                       value="{{ old('wire_bank', $contributions->wire_bank ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-btn-bar">
                        <div class="col-md-12 form-footer">
                            <div class="row">
                                <p class="offset-md-3 col-md-3">
                                    <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
                                </p>
                                <p class="col-md-3">
                                    <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
                                </p>
                            </div>
                        </div>
                    </div>

                    </form>

                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-contributions", "agency.agency-advisor."))

                    @include('agency.agency-advisor.transactions.auth-net-form')

                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-cash", "agency.agency-advisor."))
                </div>
            </div>

        </div>
    </div>

    <script>
        $(function() {
            var checkAmount = document.getElementById('id_check_amount');

            if (checkAmount && checkAmount != undefined) {
                checkAmount.addEventListener('blur', function () {
                    var amount = Number.parseFloat(checkAmount.value).toFixed(2);

                    if (!amount || Number.parseFloat(amount) <= 0) {
                        $('#id_check_amount').val(0);
                        return;
                    }
                    $('#id_check_amount').val(amount == "NaN" ? '' : amount);
                });
            }
        });

        $(function () {
            var wireAmount = document.getElementById('id_wire_amount');

            if (wireAmount && wireAmount != undefined) {
                wireAmount.addEventListener('blur', function () {
                    var amount = Number.parseFloat(wireAmount.value).toFixed(2);
                    //var amount = $('#id_wire_amount').val();
                    if (!amount || Number.parseFloat(amount) <= 0) {
                        $('#id_wire_amount').val(0);
                        return;
                    }

                    $('#id_wire_amount').val(amount == "NaN" ? '' : amount);

                });
            }
        });

        $(function () {
            if ($("#id_check_pay").is(':checked')) {
                $("#id_check_amount").prop("readonly", false);
            } else {
                $(".check-details").addClass('blur-contribution-method');
            }

            if ($("#id_wire_pay").is(':checked')) {
                $("#id_wire_amount, #id_wire_bank").prop("readonly", false);
            } else {
                $("#id_wire_amount, #id_wire_bank").prop("readonly", true);
                $(".wire-details").addClass('blur-contribution-method');
            }
        });
        function onCheckToggle(item) {
            if (item.checked) {
                $("#id_check_amount").prop("readonly", false);
                $(".check-details").removeClass('blur-contribution-method');
            } else {
                $("#id_check_amount").prop("readonly", true).val('');
                $(".check-details").addClass('blur-contribution-method');
            }
        }
        function onWireToggle(item) {
            if (item.checked) {
                $("#id_wire_amount, #id_wire_bank").prop("readonly", false);
                $(".wire-details").removeClass('blur-contribution-method');
            } else {
                $("#id_wire_amount, #id_wire_bank").prop("readonly", true).val('');
                $(".wire-details").addClass('blur-contribution-method');
            }
        }
    </script>

@endsection
