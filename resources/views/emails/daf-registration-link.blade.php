@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{$name}} </p>

    <p>To activate your Donor-Advised Fund account, click the following link. </p>

    <p><a target="_blank" href="{{ $link }}"
          style="padding: 0.5rem 1rem; color: #ffffff; text-decoration: none; background-color: #000; line-height: 2rem; border-radius: 20px">
            Activate</a></p>

    <p>Alternatively you can open the following link in your browser: <br>{{ $link }}</p>

    <p>Ignore this email if you did not initiate the DAF application request.</p>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
