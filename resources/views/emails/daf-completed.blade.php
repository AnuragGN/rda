@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{$name}} </p>

    <p>Your DAF application has been submitted successfully.</p>

    {{--<p>Alternatively you can open the following link in your browser: <br>{{ $link }}</p>--}}

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
