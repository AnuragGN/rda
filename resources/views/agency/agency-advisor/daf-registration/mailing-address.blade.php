@php
    $states = \App\Models\State::getCodeListUSA();
    $countries = \App\Models\Country::getListDAF();
    // normalize: model may be array or object
    $model = is_array($model ?? null) ? (object) $model : ($model ?? (object) []);
    $sameAddress = $model->same_address ?? 0;
@endphp

<div class="form-group row">
    <div class="col-md-11">
        <div class="form-group-title">
            <span>Mailing Address</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="same-address" class="col-md-3 col-form-label text-right pr-0">Same as above</label>
    <div class="col-md-1">
        <input type="checkbox"
            name="same_address"
            id="same-address"
            class="form-control2 checkbox-1x ml-1" 
       @checked(old('same_address', !empty($model->address_1 ?? null) && $sameAddress == 1))>
    </div>
</div>

<div class="mailing-address">

    <div class="form-group row">
        <label for="id_mailing_address_1" class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
        <div class="col-md-8">
            <input type="text" name="mailing_address_1" id="id_mailing_address_1"
                   class="form-control" minlength="3" maxlength="32"
                   value="{{ old('mailing_address_1', $model->mailing_address_1 ?? '') }}" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="id_mailing_address_2" class="col-sm-3 col-form-label text-right pr-0 form-multi-line-label">
            Address Line 2<br>(optional)
        </label>
        <div class="col-md-8">
            <input type="text" name="mailing_address_2" id="id_mailing_address_2"
                   class="form-control" maxlength="32"
                   value="{{ old('mailing_address_2', $model->mailing_address_2 ?? '') }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="id_mailing_city" class="col-md-3 col-form-label text-right pr-0">City</label>
        <div class="col-md-3">
            <input type="text" name="mailing_city" id="id_mailing_city"
                   class="form-control"
                   onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                   minlength="2" maxlength="32"
                   value="{{ old('mailing_city', $model->mailing_city ?? '') }}" required>
        </div>
        <label for="id_mailing_zip" class="col-md-2 col-form-label text-right pr-0">Zip</label>
        <div class="col-md-3">
            <input type="text" name="mailing_zip" id="id_mailing_zip"
                   class="form-control"
                   onkeypress="return /[0-9]/i.test(event.key)"
                   minlength="5" maxlength="5"
                   value="{{ old('mailing_zip', $model->mailing_zip ?? '') }}" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="id_mailing_state" class="col-md-3 col-form-label text-right pr-0">State</label>
        <div class="col-md-3">
            <select name="mailing_state" id="id_mailing_state" class="form-control" required>
                <option value=""></option>
                @foreach ($states as $code => $name)
                    <option value="{{ $code }}" {{ old('mailing_state', $model->mailing_state ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <label for="id_mailing_country" class="col-md-2 col-form-label text-right pr-0">Country</label>
        <div class="col-md-3">
            <select name="mailing_country" id="id_mailing_country" class="form-control" required>
                <option value=""></option>
                @foreach ($countries as $code => $name)
                    <option value="{{ $code }}" {{ old('mailing_country', $model->mailing_country ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
