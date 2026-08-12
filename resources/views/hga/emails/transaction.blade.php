<?php
?>
@extends('donor.emails.plain-text')

@section('content')

    <p style="font-size: 14px">Dear {{ $name }}, </p>

    <p style="font-size: 14px">Thank you for your contribution described below.</p>

    <table style="width: 100%; background: #f9f9f9; padding: 1rem; border: 1px solid #f5f5f5; border-radius: 4px;">

        <tbody>
        <tr>
            <td>From</td>
            <td>{{$transaction->account_type}} {{$transaction->account_number}}</td>
        </tr>

        <tr>
            <td>To </td>
            <td>{{$transaction->paid_to}}</td>
        </tr>

        <tr>
            <td style="width: 120px;">Amount </td>
            <td>{{ \App\Helpers\GnUtils::money($transaction->amount) }}</td>
        </tr>

        <tr>
            <td>Transaction Id </td>
            <td>{{$transaction->transaction_id}}</td>
        </tr>

        <tr>
            <td>Date </td>
            <td>{{ \App\Helpers\GnUtils::customDate($transaction->transaction_date) }}</td>
        </tr>

        @if(strlen("note") > 0)
            <tr>
                <td>Note </td>
                <td>{{$transaction->note}}</td>
            </tr>
        @endif

        </tbody>

    </table>

    <p style="font-size: 14px">No goods or services were provided to you in exchange for this contribution.
        HighGround Advisors maintains exclusive legal control over assets contributed to a donor-advised fund.
        The Internal Revenue Code requires you to obtain contemporaneous written acknowledgment of any charitable contribution of $250 or more in order to claim a charitable deduction.
        This message represents your receipt and should be retained with your tax records.
        You should consult with your advisors if you intend to claim a deduction for this gift.
        YOUR CHARITABLE DEDUCTION MAY BE DISALLOWED IF YOU ARE UNABLE TO PROVIDE THIS ACKNOWLEDGMENT TO THE INTERNAL REVENUE SERVICE.</p>

    <p style="font-size: 14px">We are grateful for the opportunity to be of service to you and to the charities you are supporting with your donor-advised fund. Please let us know if we can be of further assistance.</p>

    <p style="font-size: 14px">Regards, <br>HighGround Advisors</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
