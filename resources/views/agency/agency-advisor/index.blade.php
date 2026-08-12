<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Fund List";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])

    <div class="container">
        <div class="form-wrapper form-last hide">
            <div class="row">
                <div class="col-xl-12 col-r-15">
                    <h3 class="page-subtitle mt-2">Today's Agenda
                       
                        <div class="col-xl-3" style="margin-right: -24rem;">
                            <select class="form-control" id="priority" onchange="getTicketList();">
                                <option value="">All Priority</option>
            
                                @foreach($priorityDropdown as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
            
                            </select>
                        </div>

                        <div class="col-xl-3">
                            <select class="form-control" id="category" onchange="getTicketList();">
                                <option value="">All Ticket Type</option>
            
                                @foreach($categoryDropdown  as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                                
                            </select>
                        </div>

                    </h3>
                    @include('agency.agency-advisor.service-tickets.list-loader')

                </div>
            </div>
        </div>
        
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">

                    <h3 class="page-subtitle mt-2">Funds</h3>
                    @include('donor.funds.fund-list-loader')
                    <br>

                    @if(\App\Models\ClientInfo::isCCT())
                        @include('cct.contact.assistant')
                    @endif

                    @if(\App\Models\ClientInfo::isCCT())
                        @include('donor.funds.daf-applications')
                    @endif

                    @include('donor.funds.pending-grants-loader')

                    <br>

                    @include('donor.funds.top-charities-list')
                    {{--@include('donor.grants.list', ['showRepeat' => true, 'models' => $topCharities])--}}

                    @if(\App\Models\ClientInfo::isJCF())
                        @if(\App\Helpers\GnUtils::isDonorSession() && \App\Helpers\FileManager::hasGrantCalendarJCF())
                            <div style="float: right">
                                <a href="{{route('download-grant-calendar')}}" class="a-accent">Download Quarterly Grants Calendar<i class="fas fa-file-download"></i></a>
                            </div>
                        @endif
                        @if(\App\Helpers\GnUtils::isAgencySession() && \App\Helpers\FileManager::hasPerformanceFlashJCF())
                            <div style="float: right">
                                <a href="{{route('download-performance-flash')}}" class="a-accent">Download Performance Flash <i class="fas fa-file-download"></i></a>
                            </div>
                        @endif
                    @endif

                    @if(\App\Models\ClientInfo::isGNA() && \App\Helpers\GnUtils::isDonorSession())
                        <div class="d-none d-lg-block">
                            @include('donor.funds.home-filler')
                        </div>
                    @endif

                </div>

                @if(\App\Helpers\GnUtils::isDonorSession())
                    <div class="col-xl-4 col-l-15">
                        @include('pane-placeholder', ['classTitle' => 'mt-2', 'class' => ''])
                    </div>
                @endif
            </div>
        </div>
        <a href="{{ route('agency-home') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>

    @include('agency.agency-advisor.common-script')

    <script>

    //function define in agency.agency-advisor.common-script page
    // getTicketList('open');

    $(function() {
        jsFundListLoader.init('/m/list/funds/ajax');
        jsFundListLoader.runLoadData();

        jsPendingGrantsLoader.init('/m/ajax/pending-grants');
        jsPendingGrantsLoader.runLoadData();
    });

    // getTicketList();        
    // function getTicketList() {

    //     var category = $('#category').val();
    //     var priority = $('#priority').val();
    //     jsTicketLoader.init('/m/agency/ticket-ajax');
    //     jsTicketLoader.runLoadData('open', priority, category);
    // }
    </script>
@endsection