<?php
$prefixes = \App\Models\Prefix::getSelectable();
$suffixes = \App\Models\Suffix::getSelectable();
$profile->mail = $profile->email_address;
?>

<div class="form-group row">
    <label for="prefix" class="col-md-3 col-form-label text-right pr-0">Prefix</label>
    <div class="col-md-2 col-4">
        <select name="prefix" id="prefix" class="form-control">
            <option value="">Select</option>
            @foreach($prefixes as $value => $label)
                <option value="{{ $value }}" @selected(old('prefix', $profile->prefix) == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="first_name" class="col-md-3 col-form-label text-right pr-0">First Name</label>
    <div class="col-md-6">
        <input type="text" name="first_name" id="first_name" class="form-control" required onkeypress="return /[a-z]/i.test(event.key)" value="{{ old('first_name', $profile->first_name) }}">
    </div>
</div>

<div class="form-group row">
    <label for="last_name" class="col-md-3 col-form-label text-right pr-0">Last Name</label>
    <div class="col-md-6">
        <input type="text" name="last_name" id="last_name" class="form-control" required onkeypress="return /[a-z]/i.test(event.key)" value="{{ old('last_name', $profile->last_name) }}">
    </div>
</div>

<div class="form-group row">
    <label for="suffix1" class="col-md-3 col-form-label text-right pr-0">Suffix</label>
    <div class="col-md-2 col-4">
        <select name="suffix1" id="suffix1" class="form-control">
            <option value="">Select</option>
            @foreach($suffixes as $value => $label)
                <option value="{{ $value }}" @selected(old('suffix1', $profile->suffix1) == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

@if(!\App\Models\ClientInfo::isHGA())
<div class="form-group row">
    <label for="company_name" class="col-md-3 col-form-label text-right pr-0">Company</label>
    <div class="col-md-6">
        <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $profile->company_name) }}">
    </div>
</div>

<div class="form-group row">
    <label for="web_site" class="col-md-3 col-form-label text-right pr-0">Website</label>
    <div class="col-md-6">
        <input type="url" name="web_site" id="web_site" class="form-control" placeholder="https://example.com" value="{{ old('web_site', $profile->web_site) }}">
    </div>
</div>
@endif

<div class="form-group row hide">
    <label for="email_address" class="col-md-3 col-form-label text-right pr-0">Email</label>
    <div class="col-md-6">
        <input type="text" name="email_address" id="email_address" class="form-control" readonly value="{{ old('email_address', $profile->email_address) }}">
    </div>
</div>

<div class="form-group row">
    <div class="offset-md-3 col-md-3">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100 mt-2">Save</button>
    </div>
</div>

<div class="row row-page-title gift-history-header hide">
    <div class="col-12">
        <h1 class="page-title">
            Profile <span class="font-small fw100">{{$profile->name}}</span>
        </h1>
    </div>
</div>


{{--Prefix:--}}
{{--First Name:--}}
{{--Middle Name:--}}
{{--Last Name:--}}
{{--Suffix :--}}
{{--Email Address:--}}
{{--Home Phone Number:--}}
{{--Business Phone Number:--}}
{{--Cell Phone Number:--}}
{{--Fax Number:--}}
