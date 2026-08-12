@php
if(!isset($model)) $model = null;
@endphp

<div class="form-group row">
    <label class="col-md-2 col-form-label text-right">Name</label>
    <div class="col-md-4 pl-0">
        {{$donor->first_name}} {{$donor->last_name}}
    </div>
    <label class="col-md-2 col-form-label text-right">Date of Birth</label>
    <div class="col-md-4 pl-0">
        {{App\Helpers\GnUtils::customDate($donor->dob)}}
    </div>
</div>

<div class="form-group row">
    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_PREFNAME, $myArray))
        <label class="col-md-2 col-form-label text-right">Preferred Name</label>
        <div class="col-md-4 pl-0">
            {{$donor->preferred_name}}
        </div>
    @endif

    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_FUND_PRIVILEGES, $myArray))
        <label class="col-md-2 col-form-label text-right">Fund Privileges</label>
        <div class="col-md-4 pl-0">
            {{$donor->fund_privileges}}
        </div>
    @endif
</div>

<div class="form-group row">
    <label id="id_phone" class="col-md-2 col-form-label text-right">Phone</label>
    <div class="col-md-4 pl-0">
        {{ App\Helpers\GnUtils::formatPhoneNumber($donor->phone_number) }}
    </div>
    <label class="col-md-2 col-form-label text-right">Phone Type</label>
    <div class="col-md-4 pl-0">
        {{App\Models\PhoneType::getContactPhoneTypeLabel($donor->phone_type)}}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label text-right">Email</label>
    <div class="col-md-4 pl-0">
        {{$donor->email}}
    </div>
    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_SSN, $myArray))
        @if($donor->ssn)
            <label class="col-md-2 col-form-label text-right">SSN</label>
            <div class="col-md-4 pl-0">
                {{isset($donor->ssn_star)}}
            </div>
        @endif
    @endif
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label text-right">Address Line 1</label>
    <div class="col-md-4 pl-0">
        {{$donor->address_1}}
    </div>
    <label class="col-md-2 col-form-label text-right">Address Line 2</label>
    <div class="col-md-4 pl-0">
        {{$donor->address_2}}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label text-right">City</label>
    <div class="col-md-4 pl-0">
        {{$donor->city}}
    </div>
    <label class="col-sm-2 col-form-label text-right">Zip Code</label>
    <div class="col-sm-4 pl-0">
        {{$donor->zip}}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label text-right">State</label>
    <div class="col-md-4 pl-0">
        {{$donor->state}}
    </div>
    <label class="col-md-2 col-form-label text-right">Country</label>
    <div class="col-md-4 pl-0">
        {{$donor->country}}
    </div>
</div>
