<?php
$minAmount = \App\Models\ClientConfig::value('MIN_CONTRIBUTION_AMOUNT');
$ccFee = \App\Models\ClientConfig::value('CC_FEE');
?>

@include('errors.form-errors')

{!!  Form::hidden('contact_id', null, []) !!}

<div class="form-group row">
    {!! Form::label('fund', 'Fund Name', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::select('fund_id', $funds, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('amount', 'Amount ($)', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::number('amount', null, ['id' => 'id_floating_amount', 'required' => 'required', 'class' => 'form-control',
        'pattern' => '[0-9]+([\.,][0-9]+)?', 'step' => "0.01", 'step2' => 'any']) !!}
        <span id="ide_floating_amount" class="form-error" style="display: none">
            @if($minAmount == 1)
                The amount is not acceptable
            @else
                Amount should not be less than {{$minAmount}}
            @endif
        </span>
    </div>
</div>

@if(\App\Models\ClientConfig::value('CC_FEE_ENABLED'))
    @include(\App\Models\ClientInfo::clientViewFor("transactions.gift._cc-fee", "donor."))
@endif

@include(\App\Models\ClientInfo::clientViewFor("transactions.gift._note", "donor."))

<div class="form-group row hide">
    {!! Form::label('anonymous', 'I wish to remain anonymous', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x']) !!}
    </div>
</div>

<hr>

<div class="form-group row">
    <div class="offset-sm-3 col-sm-5 col=md-4">
            <a href="javascript:void(0);" onclick="onStripePay()" class="btn btn-accent">Continue</a>
    </div>
</div>

<div class="text-right">
    <a href="{{ url()->previous() }}" class="cancel" onclick="">Cancel</a>
</div>

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
                    amount = (amount * 100) / (100 - ccFee);
                    var fee = amount - base;
                    amount = Number.parseFloat(amount).toFixed(2);
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
