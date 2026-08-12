<?php
?>
@extends('donor.emails.plain-text')

@section('content')

    <p>Dear {{ $name }}, </p>

    <p>You may want to take action on the following {{count($grants) > 1 ? 'grants' : 'grant' }} present in your cart.</p>

    @foreach($grants as $index => $data)
        <table style="width: 100%; background: #f9f9f9; padding: 1rem; border: 1px solid #f5f5f5; border-radius: 4px;">

            <tbody>
            <tr>
                <td style="width: 120px;">Amount</td>
                <td>{{ $data['amount'] }}</td>
            </tr>

            <tr>
                <td>From fund</td>
                <td>{{ $data['fund'] }}</td>
            </tr>

            <tr>
                <td>Organization</td>
                <td>{{ $data['organization'] }}, <br><span style="font-size:90%; font-style: italic">{!! $data['address'] !!}</span> </td>
            </tr>

            @if(isset($data['org_ein']) && $data['org_ein'])
                <tr>
                    <td>EIN</td>
                    <td>{{ $data['org_ein'] }}</td>
                </tr>
            @endif

            @if(isset($data['org_contact']) && $data['org_contact'])
                <tr>
                    <td>Contact Person</td>
                    <td>{{ $data['org_contact'] }}</td>
                </tr>
            @endif

            @if(isset($data['org_phone']) && $data['org_phone'])
                <tr>
                    <td>Phone</td>
                    <td>{{ $data['org_phone'] }}</td>
                </tr>
            @endif

            @if(isset($data['org_email']) && $data['org_email'])
                <tr>
                    <td>Email</td>
                    <td>{{ $data['org_email'] }}</td>
                </tr>
            @endif

            @if(strlen($data['grant_purpose']) > 0)
                <tr>
                    <td>Grant Purpose</td>
                    <td>{{ $data['grant_purpose'] }}</td>
                </tr>
            @endif

            @if(strlen($data['note']) > 0)
                <tr>
                    <td>Note</td>
                    <td>{{ $data['note'] }}</td>
                </tr>
            @endif

            <tr>
                <td>Anonymous</td>
                <td> {{ $data['anonymous'] }}</td>
            </tr>
            </tbody>

        </table>
        @if (count($grants) > (1+$index))
            <br>
        @endif
    @endforeach

    @if(\App\Models\ClientInfo::isJCF())
        <p>Regards, <br> JCF of San Diego </p>
    @elseif
        <p>Regards, <br> Support Team </p>
    @endif

    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
    <p><small>This is a system-generated e-mail.</small></p>

@endsection
