
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }} </p>

    <p>Your new email address ({{$email}}) has been updated successfully.</p>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
