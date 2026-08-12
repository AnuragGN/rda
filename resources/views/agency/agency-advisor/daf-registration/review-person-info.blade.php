@php
$donor = json_decode(json_encode($person, true));
@endphp

<div class="form-group row mb-0">
    <label class="col-md-3 col-form-label text-right pr-0">Name</label>
    <div class="col-md-4">
        @if (isset($donor->prefix)) {{$donor->prefix}} @endif
        {{$donor->first_name}}
        @if (isset($donor->middle_name)) {{$donor->middle_name}} @endif
        {{$donor->last_name}}
        @if (isset($donor->suffix)) {{$donor->suffix}} @endif
    </div>

    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_PREFNAME, $personFields) && isset($donor->preferred_name))
        <label class="col-md-2 col-form-label text-right pr-0">Preferred Name</label>
        <div class="col-md-3">{{$donor->preferred_name}}</div>
    @endif
</div>

<div class="form-group row mb-0">
    <label class="col-md-3 col-form-label text-right pr-0">Email</label>
    <div class="col-md-4">{{$donor->email}}</div>

    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_SSN, $personFields) && isset($donor->ssn_star))
        <label class="col-md-2 col-form-label text-right pr-0">SSN</label>
        <div class="col-md-3">{{$donor->ssn_star}}</div>
    @endif
</div>

<div class="form-group row mb-0">
    <label id="id_phone" class="col-md-3 col-form-label text-right pr-0">Phone</label>
    <div class="col-md-4">{{ App\Helpers\GnUtils::formatPhoneNumber($donor->phone_number) }}</div>
    @if(!\App\Models\ClientInfo::isPFR())
        <label class="col-md-2 col-form-label text-right pr-0">Phone Type</label>
        <div class="col-md-3">{{App\Models\PhoneType::getContactPhoneTypeLabel($donor->phone_type)}}</div>
    @endif
</div>

@if (isset($donor->dob))
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label text-right pr-0">Date of Birth</label>
        <div class="col-md-4">{{App\Helpers\GnUtils::customUIDate($donor->dob)}}</div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_FUND_PRIVILEGES, $personFields) && isset($donor->fund_privileges))
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label text-right pr-0">Fund Privileges</label>
        <div class="col-md-4">{{$donor->fund_privileges}}</div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_CITIZENSHIP, $personFields) && isset($donor->citizenship))
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label text-right pr-0">Citizenship</label>
        <div class="col-md-4">{{$donor->citizenship}}</div>
    </div>
@endif

@if (isset($donor->relationship))
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label text-right pr-0">Relationship</label>
        <div class="col-md-4">{{$donor->relationship}}</div>
    </div>
@endif

@if(isset($donor->share_value))
    <div class="form-group row mb-0">
        <label class="col-md-3 col-form-label text-right pr-0">% of Giving Account</label>
        <div class="col-md-4">{{$donor->share_value}}</div>
    </div>
@endif
