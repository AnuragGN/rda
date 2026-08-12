@php
    $states = \App\Models\State::getCodeListUSA();
    $countries = \App\Models\Country::getListDAF();
    // normalize: model may be array or object
    $model = is_array($model ?? null) ? (object) $model : ($model ?? (object) []);
@endphp

<div class="form-group row">
    <div class="col-md-11">
        <div class="form-group-title">
            <span>
                @if (! \App\Models\ClientInfo::isHGA())
                    Address
                @else
                    Legal/Residential Address
                @endif
            </span>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="id_address_1" class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
    <div class="col-md-8">
        <input type="text" name="address_1" id="id_address_1" class="form-control"
               minlength="3" maxlength="32"
               value="{{ old('address_1', $model->address_1 ?? '') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="id_address_2" class="col-sm-3 col-form-label text-right pr-0 form-multi-line-label">
        Address Line 2<br>(optional)
    </label>
    <div class="col-md-8">
        <input type="text" name="address_2" id="id_address_2" class="form-control"
               maxlength="32"
               value="{{ old('address_2', $model->address_2 ?? '') }}">
    </div>
</div>

<div class="form-group row">
    <label for="id_city" class="col-md-3 col-form-label text-right pr-0">City</label>
    <div class="col-md-3">
        <input type="text" name="city" id="id_city" class="form-control"
               onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
               minlength="2" maxlength="32"
               value="{{ old('city', $model->city ?? '') }}" required>
    </div>
    <label for="id_zip" class="col-md-2 col-form-label text-right pr-0">Zip</label>
    <div class="col-md-3">
        <input type="text" name="zip" id="id_zip" class="form-control"
               onkeypress="return /[0-9]/i.test(event.key)"
               minlength="5" maxlength="5"
               value="{{ old('zip', $model->zip ?? '') }}" required>
    </div>
</div>

<div class="form-group row">
    <label for="id_state" class="col-md-3 col-form-label text-right pr-0">State</label>
    <div class="col-md-3">
        <select name="state" id="id_state" class="form-control" required>
            <option value=""></option>
            @foreach ($states as $code => $name)
                <option value="{{ $code }}" {{ old('state', $model->state ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <label for="id_country" class="col-md-2 col-form-label text-right pr-0">Country</label>
    <div class="col-md-3">
        <select name="country" id="id_country" class="form-control" required>
            <option value=""></option>
            @foreach ($countries as $code => $name)
                <option value="{{ $code }}" {{ old('country', $model->country ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
