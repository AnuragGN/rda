
<div class="form-group row">
    <div class="offset-sm-4 col-sm-8">
        If the gift is by a credit card, you may want to add credit card transaction fee.
    </div>
    <div class="offset-sm-4 col-sm-8">
        {!! Form::checkbox('add_fee_cb', true, null, ['id' => 'id_add_fee_cb', 'class' => 'form-check-label pt-0', 'onchange'=>"onFeeToggle(this)"]) !!}
        {!! Form::label('add_fee_cb', 'Add Credit Card transaction fee (' . $ccFee . '%)', ['class' => 'col-form-label']) !!}
    </div>
</div>

<div class="form-group row" id="id_add_fee" style="display: none">
    <div class="offset-sm-4 col-sm-8">
        Final gift amount = $<input id="id_amount_with_fee" class="plain-text-input" value='0' name="updatedAmount" type="text" readonly>
    </div>
</div>
