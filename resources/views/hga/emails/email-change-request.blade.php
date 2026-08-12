@extends('hga.emails.layout')

@section('content')

<p>Dear {{$name}},</p>

<p>An email address/username change was requested for your HighGround DAF portal. Please <a target="_blank" href="{{ $url }}">Click here</a> to complete this change. If you did not request to change your email address, please contact us at 214.978.3303 or dafs@highgroundadvisors.org.</p>

@endsection