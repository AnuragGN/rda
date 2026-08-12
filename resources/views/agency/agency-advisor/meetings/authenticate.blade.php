<?php
$pageTitle = 'Aunthentication';
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    {{-- <div class="form-make-grant gn-form">
                        <!-- view-meeting.blade.php -->

                        <img class="gb_Oc" src="https://www.gstatic.com/meet/google_meet_horizontal_wordmark_2020q4_1x_icon_124_40_2373e79660dabbf194273d27aa7ee1f5.png" srcset="https://www.gstatic.com/meet/google_meet_horizontal_wordmark_2020q4_1x_icon_124_40_2373e79660dabbf194273d27aa7ee1f5.png 1x, https://www.gstatic.com/meet/google_meet_horizontal_wordmark_2020q4_2x_icon_124_40_292e71bcb52a56e2a9005164118f183b.png 2x " alt="" aria-hidden="true" role="presentation" style="width:124px;height:40px" data-iml="1044.699999988079" data-atf="true">
                        <a href="{{route('view.meeting')}}" class="btn" style="font-size: 1.1em;">Meet</a>
                    </div> --}}

                    {{-- <div class="form-make-grant gn-form">
                        <!-- view-meeting.blade.php -->
                        <a href="{{ route('view.meeting') }}" class="btn" style="font-size: 1.5em;">
                            <img class="gb_Oc mr-2" src="https://www.gstatic.com/meet/google_meet_horizontal_wordmark_2020q4_1x_icon_124_40_2373e79660dabbf194273d27aa7ee1f5.png" alt="" aria-hidden="true" role="presentation" style="width:100px;height:40px; vertical-align: middle;">
                            <span class="gb_pd gb_gd">Meet</span>
                        </a>
                    </div>      --}}
                    
                    <div class="form-make-grant gn-form">
                        <!-- view-meeting.blade.php -->
                        <a href="{{ route('auth.google') }}" class="btn" style="font-size: 1.0em;">
                            <img class="gb_Oc gb_Od" src="https://ssl.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_15_2x.png" srcset="https://ssl.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_15_2x.png 1x, https://ssl.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_15_2x.png 2x " alt="" aria-hidden="true" role="presentation" style="width:40px;height:40px">
                            <span style="font-size:1.0em" >Click to authorize through Google calendar</span>
                        </a>
                    </div>     
                    
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

@include('agency.agency-advisor.common-script')
