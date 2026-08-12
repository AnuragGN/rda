<?php
$fProgramGuideLink = "https://www2.highgroundadvisors.org/l/661603/2020-12-15/4q7bhm/661603/1608064833uJdB0sSO/ProgramGuide_111120.pdf";
?>
@extends('hga.emails.layout')

@section('content')

    <p>Dear {{$name}},</p>

    <p>Thank you for choosing HighGround and for creating your donor-advised fund (DAF) portal login. You are one step closer to maximizing your charitable impact through a donor-advised fund. To activate your DAF portal and complete the DAF application,

        <a target="_blank" href="{{ $link }}">click here</a>
    </p>

    <p>For an overview of our program and answers to frequently asked questions, please read our  <a target="_blank" href="{{ $fProgramGuideLink }}">HighGround Donor-Advised Fund Program Guide</a> as you fill out the DAF application.</p>

    <p>If you have any questions along the way, please contact Katie Warren, our Client Partner Communications Specialist, at 214.978.3303 or dafs@highgroundadvisors.org. She will be happy to assist you.</p>
    <div style="display: block; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: 14px; margin: 0px; padding-bottom: 10px; color: rgb(96, 96, 96) !important; text-align: center;"><a href="https://uat-hga.giftingnetwork.org/login.go" style="text-decoration:none!important;"><img alt="" src="https://uat-hga.giftingnetwork.org/images/LogInNow_HGA.png" style="height:38px; width:100px" /> </a>&nbsp; <a href="https://www2.highgroundadvisors.org/l/661603/2020-12-15/4q7bhm/661603/1608064833uJdB0sSO/ProgramGuide_111120.pdf" style="text-decoration:none!important;"> <img alt="" src="https://uat-hga.giftingnetwork.org/images/UserGuide_HGA.png" style="height:38px; width:100px" /> </a>&nbsp; <a href="mailto:dafs@highgroundadvisors.org?subject=RE%3A%20DAF%20Online%20Portal" style="text-decoration:none!important;"> <img alt="" src="https://uat-hga.giftingnetwork.org/images/Help_2.png" style="height:38px; width:100px" /> </a>&nbsp;</div>
@endsection
