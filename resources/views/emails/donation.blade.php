<?php
?>
@extends('donor.emails.plain-text')
{{--@extends ('layouts.main')--}}

@section('content')

    <p>Dear {{ $name }}, </p>

    <p>The following are the details of your recent donation.</p>

    <table style="width: 100%; background: #f9f9f9; padding: 1rem; border: 1px solid #f5f5f5; border-radius: 4px;">

        <tbody>
        @if($donation->account_type)
            <tr>
                <td>From</td>
                <td>{{$donation->account_type}} {{$donation->account_number}}</td>
            </tr>
        @endif

        <tr>
            <td>Amount </td>
            <td>{{ \App\Helpers\GnUtils::money($donation->amount) }}</td>
        </tr>

        <tr>
            <td style="width: 120px;">To </td>
            <td>{{ $donation->getTargetName() }}</td>
        </tr>

        <tr>
            <td>Interval </td>
            <td>{{ $donation->getIntervalText() }}</td>
        </tr>

        @if(!$donation->isOneTime())
            <tr>
                <td>Start Date </td>
                <td>{{ \App\Helpers\GnUtils::customDate($donation->start_date) }}</td>
            </tr>

            <tr>
                <td>Ends after </td>
                <td>
                    @if($donation->no_end)
                        Ongoing
                    @else
                        {{ $donation->occurrences }} occurrences
                    @endif
                </td>
            </tr>
        @endif

        @if($donation->dedicated_to_name)
            <tr>
                <td>Dedicated to </td>
                <td>{{ $donation->dedicated_to_name }}</td>
            </tr>
        @endif

        @if($donation->notify_to)
            <tr><td></td><td></td></tr>

            <tr>
                <td>Notify to</td>
                <td>{{ $donation->notify_fname }} {{ $donation->notify_lname }}</td>
            </tr>

            <tr>
                <td>Address</td>
                <td>{{ $donation->notify_address_one }}</td>
            </tr>

            @if($donation->notify_address_two)
                <tr>
                    <td></td>
                    <td>{{ $donation->notify_address_two }}</td>
                </tr>
            @endif

            <tr>
                <td></td>
                <td>{{ $donation->notify_city }}, {{ $donation->notify_state }} {{ $donation->notify_zip}}</td>
            </tr>

            <tr>
                <td></td>
                <td>{{ $donation->notify_country}}</td>
            </tr>
        @endif

        <tr><td></td><td></td></tr>
        <tr>
            <td colspan="2">Personal Information </td>
        </tr>

        <tr>
            <td>Name </td>
            <td>{{ $donation->guest_fname }} {{ $donation->guest_lname }} </td>
        </tr>

        <tr>
            <td>Email </td>
            <td>{{ $donation->guest_email }}</td>
        </tr>

        <tr>
            <td>Phone # </td>
            <td>{{ $donation->guest_phone }}</td>
        </tr>

        @if($donation->donor_org_name and strlen($donation->donor_org_name) > 0)
            <tr>
                <td>Organization Name </td>
                <td>{{ $donation->donor_org_name }}</td>
            </tr>
        @endif

        <tr>
            <td>Address </td>
            <td>{{ $donation->guest_address_one }}</td>
        </tr>

        @if($donation->guest_address_two)
            <tr>
                <td></td>
                <td>{{ $donation->guest_address_two }}</td>
            </tr>
        @endif

        <tr>
            <td></td>
            <td>{{ $donation->guest_city }}, {{ $donation->guest_state }} {{ $donation->guest_zip}}</td>
        </tr>

        <tr>
            <td></td>
            <td>{{ $donation->guest_country}}</td>
        </tr>

        <tr>
            <td>Status </td>
            <td>{{$donation->displayStatus}}: {{$donation->message}}</td>
        </tr>

        </tbody>

    </table>


    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
