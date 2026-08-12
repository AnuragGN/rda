
<div class="form-group row">
    <div class="offset-sm-4 col-sm-8">
        Would you like to add 3% to your contribution to cover the payment processing fee?
    </div>
    <div class="offset-sm-4 col-sm-8">
        {!! Form::checkbox('add_fee_cb', true, null, ['id' => 'id_add_fee_cb', 'class' => 'form-check-label pt-0', 'onchange'=>"onFeeToggle(this)"]) !!}
        {!! Form::label('add_fee_cb', 'Add processing fee (' . $ccFee . '%)', ['class' => 'col-form-label']) !!}
    </div>
</div>

<div class="form-group row" id="id_add_fee" style="display: none">
    <div class="offset-sm-4 col-sm-8">
        Final gift amount = $<input id="id_amount_with_fee" class="plain-text-input" value='0' name="updatedAmount" type="text" readonly>
    </div>
</div>
