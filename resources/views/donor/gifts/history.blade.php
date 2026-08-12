<?php
$pageTitle = 'Contribution History';
$monthly = 'Contributions by Month';
if(\App\Helpers\GnUtils::isDonorSession()) {
    $pageTitle = \App\Models\ClientConfig::text('GIFT_HISTORY');
    $monthly = \App\Models\ClientConfig::text('GIFTS_BY_MONTH');
}
?>

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle])

    <section class="content">
        <div class="container history-container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="page-title mt-2">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xl-7 col-sm-12"><h3>{{ $title }}</h3></div>
                                    <div class="col-xl-5 col-sm-12 text-right">
                                        <a href="javascript:void(0);"
                                           data-message="Download the history as CSV file?"
                                           data-href="{{ route('csv-gift-history', $params) }}"
                                           class="js_confirm_file_download btn btn-light btn-sm shadowed">
                                            CSV <i class="fas fa-download"></i></a>
                                        <a target="_blank" href="{{ route('print-gift-history', $params) }}"
                                           class="btn btn-light btn-sm shadowed mr-2 ml-2">
                                            Print <i class="fas fa-print"></i></a>
                                        <a href="javascript:void(0);"
                                           onclick="sageCollapsible(this)" data-child-id="statement-filter"
                                           class="btn btn-theme btn-sm shadow-none btn-get-statement">
                                            Filter <i class="fas fa-filter"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (\App\Models\ClientInfo::isCCT())
                            @include('donor.common.history-filter-funds')
                        @else
                            @include('donor.common.history-filter', ['orgCls' => false])
                        @endif

                        {{--@include('gifts.list')--}}
                        @include('donor.common.load-more')

                        <div class="row">
                            <div class="col-12">
                                <div class="gg-total">Total {{ \App\Models\ClientInfo::isJCF() ? '' : '=' }} {{\App\Helpers\GnUtils::money($giftTotal)}}
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-lg-4">

                        <div class="chart-box mt-2">
                            <div class="title">{{ $monthly }}</div>
                            @include('donor.common.bar-chart', ['chartData' => $barChartData, 'label' => $monthly])
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </section>

@endsection
