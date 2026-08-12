<?php
$assistant = \App\Models\Contact::getAssistant();
?>

@if(\App\Models\ClientInfo::isCCT())

    @if($assistant)
        <h3 class="page-subtitle uppercase mt-2">
            <span>Contact Us</span>
        </h3>

        <div class="mb-1">Your Relationship Manager is:</div>
        <p> <span style="font-weight: 500; font-size: 1.2rem; color: #000;">{{ $assistant->name }}</span><br>
            <i class="fas fa-phone" style="font-size: 80%; color: #666;"></i>&nbsp; {{$assistant->getPrimaryPhoneNumber()}}<br>
            <i class="fas fa-envelope" style="font-size: 80%; color: #666;"></i>&nbsp; <a href="{{$assistant->email_address}}">{{ $assistant->email_address }}</a></p>


        {{-- TODO: Delete it for NT--}}
        <div class="pt-1 mb-1">Program Leadership: <small><i style="color:red;">(for NT only)</i></small></div>
        <p> <span style="font-weight: 500; font-size: 1.2rem; color: #000;">Christine A. Donovan</span><br>
            <span>Vice President and Manager, The Northern Trust Charitable Giving Program</span><br>
            <i class="fas fa-phone" style="font-size: 80%; color: #666;"></i>&nbsp; 866-494-4273<br>
            <i class="fas fa-envelope" style="font-size: 80%; color: #666;"></i>&nbsp; <a href="donoradvisedfund@ntrs.com">donoradvisedfund@ntrs.com</a></p>
        <br>
    @endif

    {{-- TODO: For NT--}}
@elseif(\App\Models\ClientInfo::isCCT())

    <h3 class="page-subtitle uppercase mt-2">
        <span>Contact Us</span>
    </h3>

    @if($assistant)
        <div class="mb-1">Your Relationship Manager is:</div>
        <p> <span style="font-weight: 500; font-size: 1.2rem; color: #000;">{{ $assistant->name }}</span><br>
            <i class="fas fa-phone" style="font-size: 80%; color: #666;"></i>&nbsp; {{$assistant->getPrimaryPhoneNumber()}}<br>
            <i class="fas fa-envelope" style="font-size: 80%; color: #666;"></i>&nbsp; <a href="{{$assistant->email_address}}">{{ $assistant->email_address }}</a></p>
    @endif

    <div class="pt-1 mb-1">Program Leadership:</div>
    <p> <span style="font-weight: 500; font-size: 1.2rem; color: #000;">Christine A. Donovan</span><br>
        <span>Vice President and Manager, The Northern Trist Charitable Giving Program</span><br>
        <i class="fas fa-phone" style="font-size: 80%; color: #666;"></i>&nbsp; 866-494-4273<br>
        <i class="fas fa-envelope" style="font-size: 80%; color: #666;"></i>&nbsp; <a href="donoradvisedfund@ntrs.com">donoradvisedfund@ntrs.com</a></p>

    <br>
@endif
