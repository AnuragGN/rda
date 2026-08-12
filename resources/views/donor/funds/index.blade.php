<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Dashboard";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-8 col-r-15">

                    <h3 class="page-subtitle uppercase mt-2">
                        {{ \App\Models\ClientInfo::isHGA() ? "Fund Overview" : "Funds" }}
                    </h3>
                    {{--@include('donor.funds.list')--}}
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
    </div>

    <script>
        $(function() {
            jsFundListLoader.init('/m/list/funds/ajax');
            jsFundListLoader.runLoadData();

            jsPendingGrantsLoader.init('/m/ajax/pending-grants');
            jsPendingGrantsLoader.runLoadData();
        });
    </script>

@endsection
