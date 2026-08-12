<form method="POST" action="{{ route('post-daf-contributions-securities') }}" id="daf-account-info-form">
    @csrf
    <input type="hidden" name="key" value="{{ $security->key }}">

<div class="form-group row">
    <label for="securities_{{ $i }}_fund_name" class="col-md-3 col-form-label text-right">Fund Name</label>
    <div class="col-md-6 pl-0">
        <input type="text" name="securities[{{ $i }}][fund_name]" id="securities_{{ $i }}_fund_name"
               class="form-control" value="{{ old('securities.'.$i.'.fund_name') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="securities_{{ $i }}_name" class="col-md-3 col-form-label text-right">Name</label>
    <div class="col-md-6 pl-0">
        <input type="text" name="securities[{{ $i }}][name]" id="securities_{{ $i }}_name"
               class="form-control" value="{{ old('securities.'.$i.'.name') }}" required>
    </div>
</div> 

<div class="form-group row">
    <label for="securities_{{ $i }}_account_number" class="col-md-3 col-form-label text-right">Custodian Account Number</label>
    <div class="col-md-6 pl-0">
        <input type="text" name="securities[{{ $i }}][account_number]" id="securities_{{ $i }}_account_number"
               class="form-control" value="{{ old('securities.'.$i.'.account_number') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="securities_{{ $i }}_shares" class="col-md-3 col-form-label text-right">Number of Shares</label>
    <div class="col-md-6 pl-0">
        <input type="text" name="securities[{{ $i }}][shares]" id="securities_{{ $i }}_shares"
               class="form-control" value="{{ old('securities.'.$i.'.shares') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="securities_{{ $i }}_amount" class="col-md-3 col-form-label text-right">Approx. Dollar Amount</label>
    <div class="col-md-6 pl-0">
        <input type="text" name="securities[{{ $i }}][amount]" id="securities_{{ $i }}_amount"
               class="form-control" value="{{ old('securities.'.$i.'.amount') }}" required>
    </div>
</div>

<div class="form-btn-bar row text-left">
    <div class="offset-md-3 col-md-3 pl-0">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-sm btn-accent w100">SAVE</button>
    </div>
    @if(!$security->isNew)
        <div class="offset-md-3 col-md-3 pl-0 text-right">
            <a href="#">Delete</a>
        </div>
    @endif
</div>

</form>
