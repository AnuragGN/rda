
<div class="form-group row">
    <label for="id_fund_name" class="col-md-3 col-form-label text-right pr-0">Security/Mutual Fund Name</label>
    <div class="col-md-6">
        <input type="text" name="fund_name" id="id_fund_name" class="form-control"
               onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
               minlength="3" maxlength="32"
               value="{{ old('fund_name', $security->fund_name ?? '') }}" required>
    </div>
</div>

@if(! (\App\Models\ClientInfo::isHGA() ))
    <div class="form-group row">
        <label for="id_shares" class="col-md-3 col-form-label text-right pr-0">No. of Share</label>
        {{--{!! Form::label("name", 'Name of Account', ['class' => 'col-md-3 col-form-label text-right']) !!}--}}
        <div class="col-md-6">
            <input type="number" name="shares" id="id_shares" class="form-control"
                   value="{{ old('shares', $security->shares ?? '') }}" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="id_name" class="col-md-3 col-form-label text-right pr-0">Name of Account</label>
        <div class="col-md-6">
            <input type="text" name="name" id="id_name" class="form-control"
                   onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                   minlength="3" maxlength="32"
                   value="{{ old('name', $security->name ?? '') }}" required>
        </div>
    </div>
@else
    <div class="form-group row">
        <label for="id_name" class="col-md-3 col-form-label text-right pr-0">Name on Account</label>
        <div class="col-md-6">
            <input type="text" name="name" id="id_name" class="form-control"
                   onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                   minlength="3" maxlength="32"
                   value="{{ old('name', $security->name ?? '') }}" required>
        </div>
    </div>
@endif

@if(! \App\Models\ClientInfo::isHGA())
    <div class="form-group row">
        <label for="id_custodian_name" class="col-md-3 col-form-label text-right pr-0">Custodian Name</label>
        <div class="col-md-6">
            <input type="text" name="custodian_name" id="id_custodian_name" class="form-control"
                   onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                   minlength="3" maxlength="32"
                   value="{{ old('custodian_name', $security->custodian_name ?? '') }}" required>
        </div>
    </div>
@endif

<div class="form-group row">
    <label for="id_account_number" class="col-md-3 col-form-label text-right pr-0">Custodian Account #</label>
    <div class="col-md-6">
        <input type="text" name="account_number" id="id_account_number" class="form-control"
               onkeypress="return /^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/i.test(event.key)"
               minlength="3" maxlength="32"
               value="{{ old('account_number', $security->account_number ?? '') }}" required>
    </div>
</div>

@if( \App\Models\ClientInfo::isHGA() )
    <div class="form-group row">
        <label for="id_shares" class="col-md-3 col-form-label text-right pr-0">Number of Shares</label>
        <div class="col-md-6">
            <input type="number" name="shares" id="id_shares" class="form-control"
                   value="{{ old('shares', $security->shares ?? '') }}" required>
        </div>
    </div>
@endif

<div class="form-group row">
    <label for="id_amount" class="col-md-3 col-form-label text-right pr-0">Approx. Amount ($)</label>
    <div class="col-md-6">
        <input type="text" name="amount" id="id_amount" class="form-control amount"
               minlength="1" maxlength="10"
               value="{{ old('amount', $security->amount ?? '') }}">
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

<script>
$('.amount').change(function () {
    var sAmount = this.value;
    var amount = Number.parseFloat(sAmount).toFixed(2);
    if (!amount || Number.parseFloat(amount) <= 0) {
        this.value = 0;
        return;
    }
    this.value = amount == "NaN" ? '' : amount;
});
</script>
