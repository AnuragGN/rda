<?php
?>
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }}, </p>

    <p>Thank you for submitting your recommendation to HighGround Advisors via Giftingnetwork.</p>

    @foreach($grants as $index => $data)
        <table style="width: 100%; background: #f9f9f9; padding: 1rem; border: 1px solid #f5f5f5; border-radius: 4px;">

            <tbody>
            <tr>
                <td style="width: 120px;">Amount</td>
                <td>{{ $data['amount'] }}</td>
            </tr>

            @if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY') && isset($data['frequency']))
                <td>{{\App\Models\GrantForm::frequencyLabel()}}</td>
                <td>{{ $data['frequency'] }}</td>
            @endif

            <tr>
                <td>From fund</td>
                <td>{{ $data['fund'] }}</td>
            </tr>

            <tr>
                <td>Organization</td>
                <td>{{ $data['organization'] }}, <br><span style="font-size:90%; font-style: italic">{!! $data['address'] !!}</span> </td>
            </tr>

            @if(strlen($data['grant_purpose']) > 0)
                <tr>
                    <td>Grant Purpose</td>
                    <td>{{ $data['grant_purpose'] }}</td>
                </tr>
            @endif

            @if(strlen($data['dedication_type']) > 0)
                <tr>
                    <td>{{ $data['dedication_type'] }}</td>
                    <td>{{ $data['grant_dedication'] }}</td>
                </tr>
            @endif

            @if(strlen($data['note']) > 0)
                <tr>
                    <td>Note</td>
                    <td>{{ $data['note'] }}</td>
                </tr>
            @endif

            </tbody>

        </table>
        @if (count($grants) > (1+$index))
            <br>
        @endif
    @endforeach

    <p>Your recommendation is being reviewed and you shall receive an email once the recommendation is approved.</p>

    <p>If you have any questions, please email to grants@highgroundadvisors.org.</p>

    <p>Regards, <br>Support Team</p>

    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">

@endsection
