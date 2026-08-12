@php
if (! isset($mAddress)) { $address = false; }
$donor = json_decode(json_encode($mAddress, true));
@endphp

<div class="form-group row">
    <div class="col-md-4 form-group-title ml-3">
        <span>Mailing Address</span>
    </div>
</div>

@if (isset($donor->same_address))

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-right pr-0">Same as above</label>
    </div>

@else

    <div class="form-group row mb-0">
        @if (isset($donor->mailing_address_1))
            <label class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
            <div class="col-md-4">{{$donor->mailing_address_1}}</div>
        @endif

        @if (isset($donor->mailing_address_2))
            <label class="col-md-2 col-form-label text-right pr-0">Address Line 2</label>
            <div class="col-md-3">{{$donor->mailing_address_2}}</div>
        @endif
    </div>

    <div class="form-group row mb-0">
        @if (isset($donor->mailing_city))
            <label class="col-md-3 col-form-label text-right pr-0">City</label>
            <div class="col-md-4">{{$donor->mailing_city}}</div>
        @endif

        @if (isset($donor->mailing_zip))
            <label class="col-sm-2 col-form-label text-right pr-0">Zip Code</label>
            <div class="col-sm-3">{{$donor->mailing_zip}}</div>
        @endif
    </div>

    <div class="form-group row mb-0">
        @if (isset($donor->mailing_state))
            <label class="col-md-3 col-form-label text-right pr-0">State</label>
            <div class="col-md-4">{{$donor->mailing_state}}</div>
        @endif

        @if (isset($donor->mailing_country))
            <label class="col-md-2 col-form-label text-right pr-0">Country</label>
            <div class="col-md-3">{{$donor->mailing_country}}</div>
        @endif
    </div>

@endif
