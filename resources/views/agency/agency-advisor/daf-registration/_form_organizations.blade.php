
<div class="form-group row">
    <label for="id_org_name" class="col-md-3 col-form-label text-right pr-0">Organization Name</label>
    <div class="col-md-6">
        <input type="text" name="org_name" id="id_org_name" class="form-control"
               onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
               value="{{ old('org_name', $organization->org_name ?? '') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="id_contact_name" class="col-md-3 col-form-label text-right pr-0">Contact Name</label>
    <div class="col-md-6">
        <input type="text" name="contact_name" id="id_contact_name" class="form-control"
               onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
               maxlength="32"
               value="{{ old('contact_name', $organization->contact_name ?? '') }}" required>
    </div>
</div>
  
<div class="form-group row">
    <label for="id_giving" class="col-md-3 col-form-label text-right pr-0">% of Giving Account</label>
    <div class="col-md-3">
        <input type="number" name="giving" id="id_giving" class="form-control"
               onchange="handleGivingChange(this);"
               value="{{ old('giving', $organization->giving ?? '') }}" required>
    </div>
</div>

<div class="form-group row">
    @if (! \App\Models\ClientInfo::isHGA())
        <label for="id_ein" class="col-md-3 col-form-label text-right pr-0">EIN</label>
    @else
        <label for="id_ein" class="col-md-3 col-form-label text-right pr-0">Federal Tax Payer ID#</label>
    @endif

    <div class="col-md-3">
        <input type="text" name="ein" id="id_org_ein" class="form-control org_ein"
               maxlength="10" placeholder="22-7777777"
               value="{{ old('ein', $organization->ein ?? '') }}">
    </div>
</div>

<div class="form-group row">
    <label for="id_phone" class="col-md-3 col-form-label text-right pr-0">Phone</label>
    <div class="col-md-3">
        <input type="text" name="phone_number" id="id_phone" class="form-control phone_number"
               onkeypress="return /[0-9]/i.test(event.key)"
               value="{{ old('phone_number', $organization->phone_number ?? '') }}">
    </div>
</div>

@include('agency.agency-advisor.daf-registration.address', ['model' => $organization])

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
    function handleGivingChange(input) {
        if (input.value < 1) {
            input.value = 1;
            alert('Minimum 1 allowed');
        }
        if (input.value > 100){
            input.value = 100;
            alert('Maximum 100 allowed');
        }
    }
</script>
