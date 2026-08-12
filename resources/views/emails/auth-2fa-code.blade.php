
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }} </p>

    <p>Please use this temporary code to log in:</p>

    <p style="font-size: 200%; font-weight: bold">
        {{$code}}
    </p>

    <p>The code will expire in 15 minutes.</p>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection


