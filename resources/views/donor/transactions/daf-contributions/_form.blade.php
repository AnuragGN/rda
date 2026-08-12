<?php
$minAmount = \App\Models\ClientConfig::value('DAF_MIN_CONTRIBUTION_CC_AMOUNT');
$ccFee = \App\Models\ClientConfig::value('CC_FEE');
?>

{{--@include('errors.form-errors')--}}

{!!  Form::hidden('contact_id', null, []) !!}
{!!  Form::hidden('fund_id', null, []) !!}

<div class="form-group row">
    {!! Form::label('amount', 'Amount ($)', ['class' => 'col-md-4 col-form-label text-right pr-0']) !!}
    <div class="col-sm-3">
        {!! Form::number('amount', null, ['id' => 'id_floating_amount', 'required' => 'required', 'class' => 'form-control',
        'pattern' => '[0-9]+([\.,][0-9]+)?', 'step' => "0.01", 'step2' => 'any']) !!}
    </div>

    <div id="ide_floating_amount" class="row offset-md-4 form-error" style="display: none">
        {{--@if($minAmount == 0)--}}
        {{--<span >The amount is not acceptable</span>--}}
        {{--@else--}}
        <span>&nbsp;Amount should not be less than ${{$minAmount}}</span>
        {{--@endif--}}
    </div>
</div>

@if(\App\Models\ClientConfig::value('CC_FEE_ENABLED'))
    @include(\App\Models\ClientInfo::clientViewFor("transactions.daf-contributions._cc-fee", "donor."))
@endif

<div class="form-group row">
    <div class="offset-sm-4 col-sm-3">
    <a href="javascript:void(0);" onclick="onShowPaymentMethod()" class="btn btn-sm btn-accent w100">Pay Now</a>
    </div>
</div>
{{--@include(\App\Models\ClientInfo::clientViewFor("transactions.gift._note", "donor."))--}}

<div class="form-group row hide">
    {!! Form::label('anonymous', 'I wish to remain anonymous', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x']) !!}
    </div>
</div>

{{--<hr>--}}

{{--<div class="form-group row">--}}
    {{--<div class="offset-sm-3 col-sm-5 col=md-4">--}}
        {{--<a href="javascript:void(0);" onclick="onShowPaymentMethod()" class="btn btn-accent">Continue</a>--}}
        {{--{!! Form::submit('Select payment method', ['name' => 'save', 'class' => 'btn btn-accent btn-lg w100']) !!}--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="text-right">--}}
    {{--<a href="{{ url()->previous() }}" class="cancel" onclick="">Cancel</a>--}}
{{--</div>--}}

<script>
    $(function() {
        var amountInput = document.getElementById('id_floating_amount');

        if (amountInput && amountInput != undefined) {
            amountInput.addEventListener('blur', function () {
                setTimeout(function (){
                    var amount = $('#id_floating_amount').val();
                    if (!amount || Number.parseFloat(amount) <= 0) {
                        $('#id_amount_with_fee').html(0);
                        return;
                    }
                    var ccFee = Number.parseFloat({{$ccFee}}).toFixed(2);
                    console.log("ccFee=" + ccFee);

                    var base = amount;
                    // console.log("amount is " + amount);
                    amount = (amount * 100) / (100 - ccFee);
                    var fee = amount - base;
                    amount = Number.parseFloat(amount).toFixed(2);
                    // console.log("new amount is " + amount);
                    // $('#id_amount_with_fee').html(base + '+' + fee + '=' + amount );
                    $('#id_amount_with_fee').val(amount);
                }, 100);
            });
        }
    });

    function onFeeToggle(item) {
        if (item.checked) {
            $('#id_add_fee').show(400);
        } else {
            $('#id_add_fee').hide(400);
        }
    }
</script>

<script>
    var authCustomData = {};

    function onShowPaymentMethod() {
        var values = {};
        $.each($('#contributions_credit_card_form').serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });

       //  console.log("FORM DATA: values: " + JSON.stringify(values));
        var minAmount = {{$minAmount}};

        if (values.amount == undefined || values.amount < minAmount) {
            $('#ide_floating_amount').show();
            return;
        } else {
            $('#ide_floating_amount').hide();
        }

        authCustomData.payTargetType = 'daf';
        // check if add cc transaction fee is present and checked
        authCustomData.payAmount = values.add_fee_cb ? values.updatedAmount : values.amount;
        authCustomData.payTargetId = values.fund_id;
        authCustomData.payContactId = values.contact_id;
        //authCustomData.payNote = values.note;
        // console.log("FORM DATA: authCustomData: " + JSON.stringify(authCustomData));

        $(".AcceptUI").click();

        setTimeout(function (){
            document.getElementById("id_pay_amount").value = authCustomData.payAmount;
            document.getElementById("id_pay_target_type").value = authCustomData.payTargetType;
            document.getElementById("id_pay_target_id").value = authCustomData.payTargetId;
            document.getElementById("id_pay_contact_id").value = authCustomData.payContactId;
            //document.getElementById("id_pay_note").value = authCustomData.payNote;
        }, 2000);

    }
</script>
