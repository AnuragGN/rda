
<div class="form-group row">
    <div class="offset-sm-4 col-sm-8">
        If the gift is by a credit card, you may want to add credit card transaction fee.
    </div>
    <div class="offset-sm-4 col-sm-8">
        <input type="checkbox" name="add_fee_cb" id="id_add_fee_cb" value="1"
               class="form-check-label pt-0" onchange="onFeeToggle(this)">
        <label for="id_add_fee_cb" class="col-form-label">Add Credit Card transaction fee ({{ $ccFee }}%)</label>
    </div>
</div>

<div class="form-group row" id="id_add_fee" style="display: none">
    <div class="offset-sm-4 col-sm-8">
        Final gift amount = $<input id="id_amount_with_fee" class="plain-text-input" value='0' name="updatedAmount" type="text" readonly>
    </div>
</div>
