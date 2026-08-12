<?php
?>
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }}, </p>

    <p>The following are the details of your recent transaction.</p>

    <table style="width: 100%; background: #f9f9f9; padding: 1rem; border: 1px solid #f5f5f5; border-radius: 4px;">

        <tbody>
        <tr>
            <td>From</td>
            <td>{{$transaction->account_type}} {{$transaction->account_number}}</td>
        </tr>

        <tr>
            <td>To </td>
            <td>{{ \App\Models\DAFAccount::getDAFFundNameById($transaction->target_id)}}</td>
            {{--<td>{{$transaction->paid_to}}</td>--}}
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
            <td>Reference Id </td>
            <td>{{$transaction->ref_id}}</td>
        </tr>

        <tr>
            <td>Date </td>
            <td>{{ \App\Helpers\GnUtils::customDate($transaction->transaction_date) }}</td>
        </tr>

        {{--@if(strlen("note") > 0)--}}
            {{--<tr>--}}
                {{--<td>Note </td>--}}
                {{--<td>{{$transaction->note}}</td>--}}
            {{--</tr>--}}
        {{--@endif--}}

        <tr>
            <td>Status </td>
            <td>{{$transaction->displayStatus}}: {{$transaction->message}}</td>
        </tr>

        </tbody>

    </table>


    <p>Regards, <br>Support Team</p>
    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
