
@extends('donor.emails.plain-text')

@section('content')

    <p>{{ $contact->name }} (ContactId={{ $contact->contact_id }}) updated the following information: </p>

    <ol>
        @foreach($changes as $change)
            <li>
                {{$change['key']}} changed from {{ '"' . $change['from'] . '"'}} to {{ '"' . $change['to'] . '"'}}
            </li>
        @endforeach
    </ol>

    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection


