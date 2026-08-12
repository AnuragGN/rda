<?php
$pageTitle = \App\Helpers\GnUtils::isDonorSession() ? 'Grant History' : 'Disbursement History';

?>
@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => 12])

        <div class="container history-container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="page-title mt-2">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xl-7 col-sm-12"><h3>{{ $fund->name }}</h3></div>
                                    <div class="col-xl-5 col-sm-12 text-right">
                                        <a href="javascript:void(0);"
                                           data-message="Are you sure you want to download your grant history?"
                                           data-href="{{ route('csv-grant-history', $params) }}"
                                           class="js_confirm_file_download btn btn-light btn-sm shadowed">
                                            CSV <i class="fas fa-download"></i></a>
                                        <a target="_blank" href="{{ route('print-grant-history', $params) }}"
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

                        @include('donor.common.history-filter', ['orgCls' => true])

                        {{--@include('grants.list', ['showRepeat' => true])--}}
                        @include('donor.common.load-more')

                        <div class="row">
                            <div class="col-12">
                                <div class="gg-total">
                                    Total {{ \App\Models\ClientInfo::isJCF() ? '' : '=' }} {{\App\Helpers\GnUtils::money($grantTotal)}}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4 col-md-8 col-sm-10 col-12">

                        @if(\App\Helpers\GnUtils::isDonorSession())
                            @if(!\App\Models\ClientInfo::isJCF())
                                <div class="chart-box mt-2">
                                    <div class="title">Grants by Month</div>
                                    @include('donor.common.bar-chart', ['chartData' => $barChartData, 'label' => 'Grants by Month'])
                                </div>
                            @endif

                            <div class="chart-box">

                                <div class="title">
                                    {{ \App\Helpers\GnUtils::isDonorSession() ? 'Grant Distribution' : 'Disbursement Distribution' }}
                                    <span class="amount">{{\App\Helpers\GnUtils::money($grantTotal)}}</span>
                                </div>

                                <div class="chart-wrapper">
                                    <div class="chart-container chart" style="height: {{300+count($pieChartData)*22}}px">
                                        <canvas id="id_gd_pie_chart" width="300" height="600"></canvas>
                                    </div>
                                </div>

                                <div id="id_show_more" class="show-more">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-show-more" onclick="showMoreHistory()">Show more</a>
                                </div>

                                <p style="font-style: italic; padding: 1rem;">
                                    @if(\App\Models\ClientInfo::isNIF())
                                        You may manipulate this pie chart by removing grants that may skew it; to do so, just click on the name of the grantee, and to reinsert the grantee, click again.
                                    @elseif(\App\Models\ClientInfo::isJCF())
                                        To adjust pie chart, click on a grantee name to remove or reinsert grantee(s) distributions.
                                    @else
                                        Clicking on a grantee will remove it from the pie chart – to reinsert the grantee click it again.
                                    @endif
                                </p>
                                {{--@include('common.pie-chart', ['chartData' => $pieChartData])--}}
                            </div>

                            @if(\App\Models\ClientInfo::isJCF())
                                <div class="chart-box mt-2">
                                    <div class="title">Grants by Quarter</div>
                                    @include('donor.common.bar-chart', ['chartData' => $barChartData, 'label' => 'Grants by Quarter'])
                                </div>
                            @endif

                        @endif

                    </div>

                </div>
            </div>
        </div>

    <script>
        $(function(){
            var gsData = <?=json_encode($pieChartData)?>;
            var pie = new PieChart();
            pie.init(gsData, 'id_gd_pie_chart');
            pie.setOptions();
            pie.draw();
        });
        function showMoreHistory(){
            $('.chart-wrapper').removeClass('chart-wrapper');
            $('#id_show_more').addClass('hide');
        }
    </script>

@endsection
