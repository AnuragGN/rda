<?php
?>
@extends('fig.emails.plain-text')
{{--@extends ('layouts.main')--}}

@section('content')

    <div style="width: 100%; background: #fff;">


        <p style="margin-top: 2rem;">
            {{ $name }}<br/>
            {{ $donation->guest_address_one }}<br/>
            @if($donation->guest_address_two)
                {{ $donation->guest_address_two }}<br/>
            @endif
            {{ $donation->guest_city }}, {{ $donation->guest_state }} {{ $donation->guest_zip}}
        </p>

        <p style="margin-top: 2rem;">Dear {{ $name }}, </p>

        <p>Thank you for your generous gift of
            {{ \App\Helpers\GnUtils::money($donation->amount) }}
            to
            {{ $donation->getTargetName() }}
            dated
            {{ \App\Helpers\GnUtils::customDate($donation->created_on) }}.<br/>
            The Community Foundation for Inspired Giving appreciates your support.</p>

        <p>Blessings,<br/> Joseph C. Moravec<br/> President</p>


    </div>

@endsection
