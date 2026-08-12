@extends('hga.emails.layout')

@section('content')

    <p>Dear {{ $name }},</p>

    <p>You or someone has requested to reset the password. Please <a target="_blank" href="{{ $url }}">Click here</a> to reset your password. If you have not requested, please contact the Highground Advisors at info@highgroundadvisors.com</p>

@endsection