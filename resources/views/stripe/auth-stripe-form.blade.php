<?php
$minAmount = \App\Models\ClientConfig::value('MIN_CONTRIBUTION_AMOUNT');
$ccFee = \App\Models\ClientConfig::value('CC_FEE');
?>
<form id="paymentFormStripe"
      method="POST"
      action="{{route('stripe.make-payment')}}">
    {{-- security token --}}
    {{ csrf_field() }}

    {{-- custom fields --}}
    <input type="hidden" name="payAmount" id="id_pay_amount" />
    <input type="hidden" name="payTargetType" id="id_pay_target_type" />
    <input type="hidden" name="payTargetId" id="id_pay_target_id" />
    <input type="hidden" name="payContactId" id="id_pay_contact_id" />
    <input type="hidden" name="payNote" id="id_pay_note" />

</form>

<script>
    var authCustomData = {};
    function onStripePay() {
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
        // check if add cc transaction fee is present and checked
        authCustomData.payAmount = values.add_fee_cb ? values.updatedAmount : values.amount;
        authCustomData.payTargetId = values.fund_id;
        authCustomData.payContactId = values.contact_id;
        authCustomData.payNote = values.note;

        document.getElementById("id_pay_amount").value = authCustomData.payAmount;
        document.getElementById("id_pay_target_type").value = authCustomData.payTargetType;
        document.getElementById("id_pay_target_id").value = authCustomData.payTargetId;
        document.getElementById("id_pay_contact_id").value = authCustomData.payContactId;
        document.getElementById("id_pay_note").value = authCustomData.payNote;

        document.getElementById("paymentFormStripe").submit();

    }

</script>
