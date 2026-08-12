
<form method="POST" action="{{ route('post-agency-daf-contributions-cash', $id) }}" id="daf-account-info-form">
    @csrf

{{-- 1. ACH --}}
<div class="form-group row">
    <div class="col-md-2">
        <div class="contribution_type">ACH</div>
    </div>

    <label for="id_ach_amount" class="col-md-2 col-form-label text-right">ACH Amount</label>
    <div class="col-md-2 pl-0 pr-0">
        <input type="number" name="ach_amount" id="id_ach_amount" class="form-control"
               value="{{ old('ach_amount', $contributions->ach_amount ?? '') }}">
    </div>
</div>
<div class="form-group row">
    <label for="id_ach_bank" class="col-md-4 col-form-label text-right">ACH Bank Name</label>
    <div class="col-md-5 pl-0 pr-0">
        <input type="text" name="ach_bank" id="id_ach_bank" class="form-control"
               value="{{ old('ach_bank', $contributions->ach_bank ?? '') }}">
    </div>
</div>

{{-- 2. Wire --}}
<div class="form-group row">
    <div class="col-md-2">
        <div class="contribution_type">WIRE</div>
    </div>

    <label for="id_wire_amount" class="col-md-2 col-form-label text-right">Wire Amount</label>
    <div class="col-md-2 pl-0 pr-0">
        <input type="number" name="wire_amount" id="id_wire_amount" class="form-control"
               value="{{ old('wire_amount', $contributions->wire_amount ?? '') }}">
    </div>
</div>
<div class="form-group row">
    <label for="id_wire_bank" class="col-md-4 col-form-label text-right">Wire Bank Name</label>
    <div class="col-md-5 pl-0 pr-0">
        <input type="text" name="wire_bank" id="id_wire_bank" class="form-control"
               value="{{ old('wire_bank', $contributions->wire_bank ?? '') }}">
    </div>
</div>

{{-- CHECK --}}
<div class="form-group row">
    <div class="col-md-2">
        <div class="contribution_type">CHECK</div>
    </div>

    <label for="id_check_amount" class="col-md-2 col-form-label text-right">Check Amount</label>
    <div class="col-md-2 pl-0 pr-0">
        <input type="number" name="check_amount" id="id_check_amount" class="form-control"
               value="{{ old('check_amount', $contributions->check_amount ?? '') }}">
    </div>
</div>
<div class="form-group row">
    <label for="id_check_bank" class="col-md-4 col-form-label text-right">Check Bank Name</label>
    <div class="col-md-5 pl-0 pr-0">
        <input type="text" name="check_bank" id="id_check_bank" class="form-control"
               value="{{ old('check_bank', $contributions->check_bank ?? '') }}">
    </div>
</div>

<div class="form-btn-bar">
    <div class="col-12 form-footer">
        <div class="row">
            <p class="offset-md-3 col-md-3 pl-0">
                <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-theme w100">SAVE</button>
            </p>
            <p class="col-md-3">
                <button type="submit" name="save_next" class="btn btn-theme w100">Save & Next</button>
            </p>
        </div>
    </div>
</div>

</form>
