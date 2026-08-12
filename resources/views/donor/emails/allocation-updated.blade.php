
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }} </p>

    <p>Your request to make the following changes has been saved.</p>

    @foreach($allocations as $allocation)
        <div>{{ $allocation->pool_name }}: {{$allocation->requested_allocation}}%</div>
    @endforeach

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
