<?php
$states = \App\Models\State::getCodeListUSA();
$countries = \App\Models\Country::getListUSAOnly(); // getList();
?>

{{--Address 1:--}} {{--P.O. Box 19075--}}
{{--Address 2:--}} {{--Atherton Drive--}}
{{--City:--}} {{--Newark--}}
{{--State:--}}
{{--Zip Code:--}} {{--07195-0078--}}
{{--Country:--}}

<input type="hidden" name="address_type" value="{{$address->address_type}}" />
<input type="hidden" name="id" value="{{$address->contact_address_id}}" />

<div class="form-group row pt-2-md">
    <label for="address_1" class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
    <div class="col-md-8">
        <input type="text" name="address_1" id="address_1" class="form-control" required value="{{ old('address_1', $address->address_1) }}">
    </div>
</div>

<div class="form-group row">
    <label for="address_2" class="col-md-3 col-form-label text-right pr-0">Address Line 2</label>
    <div class="col-md-8">
        <input type="text" name="address_2" id="address_2" class="form-control" placeholder="optional" value="{{ old('address_2', $address->address_2) }}">
    </div>
</div>

<div class="form-group row">
    <label for="city" class="col-md-3 col-form-label text-right pr-0">City</label>
    <div class="col-md-5">
        <input type="text" name="city" id="city" class="form-control" required value="{{ old('city', $address->city) }}">
    </div>
</div>

<div class="form-group row">
    <label for="state" class="col-md-3 col-form-label text-right pr-0">State</label>
    <div class="col-md-5">
        <select name="state" id="state" class="form-control" required>
            <option value="">Select State</option>
            @foreach($states as $value => $label)
                <option value="{{ $value }}" @selected(old('state', $address->state) == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="zip" class="col-md-3 col-form-label text-right pr-0">ZIP</label>
    <div class="col-md-2 col-4">
        <input type="text" name="zip" id="id_zip" class="form-control" required value="{{ old('zip', $address->zip) }}">
    </div>
</div>

<div class="form-group row">
    <label for="country" class="col-md-3 col-form-label text-right pr-0">Country</label>
    <div class="col-md-5">
        <select name="country" id="country" class="form-control" disabled>
            <option value="USA" selected>USA</option>
        </select>
        <input type="hidden" name="country" value="USA">
    </div>
</div>

<hr>

<div class="form-group row">
    <div class="offset-md-3 col-md-5">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">Save</button>
    </div>
</div>
