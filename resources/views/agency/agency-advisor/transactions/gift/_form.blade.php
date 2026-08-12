@php
$minAmount = \App\Models\ClientConfig::value('MIN_CONTRIBUTION_AMOUNT');
$ccFee = \App\Models\ClientConfig::value('CC_FEE');
@endphp

@include('errors.form-errors')

<input type="hidden" name="contact_id" value="{{ old('contact_id') }}">

<div class="form-group row">
    <label for="fund_id" class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
    <div class="col-sm-9">
        <select name="fund_id" id="fund_id" class="form-control">
            <option value=""></option>
            @foreach ($funds as $val => $label)
                <option value="{{ $val }}" {{ old('fund_id') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="id_floating_amount" class="col-sm-3 col-form-label text-right pr-0">Amount ($)</label>
    <div class="col-sm-9">
        <input type="number" name="amount" id="id_floating_amount" class="form-control"
               required pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
               value="{{ old('amount') }}">
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
    @include(\App\Models\ClientInfo::clientViewFor("transactions.gift._cc-fee", "agency.agency-advisor."))
@endif

@include(\App\Models\ClientInfo::clientViewFor("transactions.gift._note", "agency.agency-advisor."))

<div class="form-group row hide">
    <label for="anonymous" class="col-sm-3 col-form-label text-right pr-0">I wish to remain anonymous</label>
    <div class="col-sm-9">
        <input type="checkbox" name="anonymous" id="anonymous" class="form-control2 checkbox-1x" value="1"
               {{ old('anonymous') ? 'checked' : '' }}>
    </div>
</div>

<hr>

<div class="form-group row">
    <div class="offset-sm-3 col-sm-5 col=md-4">
        <a href="javascript:void(0);" onclick="onShowPaymentMethod()" class="btn btn-accent">Continue</a>
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

<script>
    var authCustomData = {};

    function onShowPaymentMethod() {
        var values = {};
        $.each($('#credit-fund-form').serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });

        var minAmount = {{$minAmount}};
        if (values.amount == undefined || values.amount < minAmount) {
            $('#ide_floating_amount').show();
            return;
        } else {
            $('#ide_floating_amount').hide();
        }

        authCustomData.payTargetType = 'fund';
        authCustomData.payAmount = values.add_fee_cb ? values.updatedAmount : values.amount;
        authCustomData.payTargetId = values.fund_id;
        authCustomData.payContactId = values.contact_id;
        authCustomData.payNote = values.note;

        $(".AcceptUI").click();

        setTimeout(function (){
            document.getElementById("id_pay_amount").value = authCustomData.payAmount;
            document.getElementById("id_pay_target_type").value = authCustomData.payTargetType;
            document.getElementById("id_pay_target_id").value = authCustomData.payTargetId;
            document.getElementById("id_pay_contact_id").value = authCustomData.payContactId;
            document.getElementById("id_pay_note").value = authCustomData.payNote;
        }, 2000);
    }
</script>
