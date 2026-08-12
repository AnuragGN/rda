
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }} </p>

    <p>We received a request to set this email address as you primary email address. Please click the following link to change your email address. </p>

    <p><a target="_blank" href="{{ $url }}"
          style="padding: 0.5rem 1rem; color: #ffffff; text-decoration: none; background-color: #000; line-height: 2rem; border-radius: 20px">
            Change Email</a></p>

    <p>Alternatively you can open the following link in your browser: <br>{{ $url }}</p>

    <p>Ignore this email if you did not initiate the change email request.</p>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
