
@extends('hga.emails.layout')

@section('content')

    <p>Dear {{ $name }} </p>

    {{--<p>Your new email address ({{$email}}) has been updated successfully.</p>--}}
    <p>Your new email address/username has been updated successfully.</p>
    <p>As always, if you have any questions, please contact Katie Warren, our Client Partner Communications Specialist, at 214.978.3303 or dafs@highgroundadvisors.org.</p>
    <p>It is our privilege to serve donors like you, whose generosity is transforming lives.</p>

@endsection
