<?php
$applications = \App\Models\DAFAccount::getApplications();
?>
@if(\App\Models\Config::enableDafAppDonor())

    @if(count($applications))
        <h3 class="page-subtitle uppercase mt-2">
            <span>DAF Applications</span>
        </h3>

        @foreach($applications as $application)
            <a href="{{route('daf-account-info', $application->id)}}">
            {{$application['fund_name']}} (<span style="text-transform: capitalize;"> {{$application['status']}}</span>) <br>
            </a>
        @endforeach

        <div style="text-align: right;">
            <i class="nav-icon fas fa-plus-circle"></i> <a href="{{route('new-daf-application')}}">Create New DAF Application</a>
        </div>
        <br>
        <br>
    @else
        <div style="text-align: right;">
            <i class="nav-icon fas fa-plus-circle"></i> <a href="{{route('new-daf-application')}}">Create New DAF Application</a>
        </div>
        <br>
        <br>
    @endif
@endif
