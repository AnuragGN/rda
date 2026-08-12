@php
if (! isset($address)) { $address = false; }
$donor = json_decode(json_encode($address, true));
@endphp

<div class="form-group row">
    <div class="col-md-4 form-group-title ml-3">
        <span>
            @if(!\App\Models\ClientInfo::isHGA())
                Address
            @else
                Legal/Residential Address
            @endif
        </span>
    </div>
</div>

<div class="form-group row mb-0">
    @if (isset($donor->address_1))
        <label class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
        <div class="col-md-4">{{$donor->address_1}}</div>
    @endif

    @if (isset($donor->address_2))
        <label class="col-md-2 col-form-label text-right pr-0">Address Line 2</label>
        <div class="col-md-3">{{$donor->address_2}}</div>
    @endif
</div>

<div class="form-group row mb-0">
    @if (isset($donor->city))
        <label class="col-md-3 col-form-label text-right pr-0">City</label>
        <div class="col-md-4">{{$donor->city}}</div>
    @endif

    @if (isset($donor->zip))
        <label class="col-sm-2 col-form-label text-right pr-0">Zip Code</label>
        <div class="col-sm-3">{{$donor->zip}}</div>
    @endif
</div>

<div class="form-group row mb-0">
    @if (isset($donor->state))
        <label class="col-md-3 col-form-label text-right pr-0">State</label>
        <div class="col-md-4">{{$donor->state}}</div>
    @endif

    @if (isset($donor->country))
        <label class="col-md-2 col-form-label text-right pr-0">Country</label>
        <div class="col-md-3">{{$donor->country}}</div>
    @endif
</div>
