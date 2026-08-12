@extends('donor.emails.plain-text')

@section('content')

    <p><span style="font-weight: 600">{{ $contact->name }}</span> has requested more information.</p>

    <p>
        <span style="font-weight: 600">Selected Points:</span>
        @foreach($contactUs['additional_info_array'] as $item)
            <br/>- {{$item}}
        @endforeach
    </p>

    <p>
        <span style="font-weight: 600">Notes:</span>
        <br/> {{ $contactUs['comment'] }}
    </p>

    <p>
        <span style="font-weight: 600">For Organization:</span>
        <br/><span style="font-weight: 600">Name:</span> {{ $targetInfo['name'] }}
        <br/><span style="font-weight: 600">Address:</span> {{ $targetInfo['address'] }}
    </p>

    <p>
        <span style="font-weight: 600">Contact Info: </span>
        <br/><span style="font-weight: 600">Name:</span> {{ $contactUs['contact_name'] }}
        <br/><span style="font-weight: 600">Phone:</span> {{ $contactUs['contact_phone'] }}
        <br/><span style="font-weight: 600">Email:</span> {{ $contactUs['contact_email'] }}
    </p>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
