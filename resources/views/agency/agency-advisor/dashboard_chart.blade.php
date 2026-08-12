<?php
$pageTitle = 'Dashboard';
?>
@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => 12])

    <div class="container history-container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="chart-box mt-2">
                        <div class="title">Fund Balances</div>
                        @include('agency.agency-advisor.chart.fund-balance-bar-chart', ['fundsBalanceChartData' => $fundsBalanceChartData])   
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="chart-box mt-2">
                        <div class="title">Grants (Last 30 days)</div>
                        <div id="funds-page-1" style="">
                            <div class="row">

                                <div class="col-12">
                                    <div class="fund-pool pool-default">
                                        <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                        title="Click to Expand / Collapse" data-target-id="fundGrantDiv-1">
                                            <span class="name">
                                            <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                            <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                            The Stephen Family Fund</span>
                                            <span class="amount"> $16,000.00</span>
                                        </a>
                                        <div class="pool-values" id="fundGrantDiv-1" style="display: none;">
                                            <div class="">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" title="Click to Expand / Collapse" data-target-id="orgGrantDiv-1-1">
                                                    <span class="name" style="font-weight: 450;">
                                                        <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                        <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                        American Law Institute
                                                    </span>
                                                    <span class="amount" style="font-weight: 400;"> $10,000.00</span>
                                                </a>
                                                <div class="pool-values" id="orgGrantDiv-1-1" style="display: none;">
                                                    <div class="fund-kv">
                                                        <span><small>Cliff Stevenson</small></span>
                                                        <span><small>$6,000.00</small></span>
                                                    </div>
                                                    <div class="fund-kv">
                                                        <span><small>Richard Cherry</small></span>
                                                        <span><small>$4,000.00</small></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                                title="Click to Expand / Collapse" data-target-id="orgGrantDiv-1-2">
                                                    <span class="name" style="font-weight: 450;">
                                                        <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                        <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                        Acme Charitable Organization
                                                    </span>
                                                    <span class="amount" style="font-weight: 400;"> $6,000.00</span>
                                                </a>
                                                <div class="pool-values" id="orgGrantDiv-1-2" style="display: none;">
                                                    <div class="fund-kv">
                                                        <span><small>Jeremy Pearl</small></span>
                                                        <span><small>$4,000.00</small></span>
                                                    </div>
                                                    <div class="fund-kv">
                                                        <span><small>Phil Sheehy</small></span>
                                                        <span><small>$2,000.00</small></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="fund-pool pool-default">
                                        <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                        title="Click to Expand / Collapse" data-target-id="fundGrantDiv-2">
                                            <span class="name">
                                            <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                            <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                            The Cherry Fund</span>
                                            <span class="amount"> $12,000.00</span>
                                        </a>
                                        <div class="pool-values" id="fundGrantDiv-2" style="display: none;">
                                            <div class="">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" title="Click to Expand / Collapse" data-target-id="orgGrantDiv-2-1">
                                                    <span class="name" style="font-weight: 450;">
                                                        <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                        <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                        American Law Institute
                                                    </span>
                                                    <span class="amount" style="font-weight: 400;"> $7,000.00</span>
                                                </a>
                                                <div class="pool-values" id="orgGrantDiv-2-1" style="display: none;">
                                                    <div class="fund-kv">
                                                        <span><small>Cliff Stevenson</small></span>
                                                        <span><small>$4,000.00</small></span>
                                                    </div>
                                                    <div class="fund-kv">
                                                        <span><small>Richard Cherry</small></span>
                                                        <span><small>$3,000.00</small></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                                title="Click to Expand / Collapse" data-target-id="orgGrantDiv-2-2">
                                                    <span class="name" style="font-weight: 450;">
                                                        <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                        <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                        Acme Charitable Organization
                                                    </span>
                                                    <span class="amount" style="font-weight: 400;"> $5,000.00</span>
                                                </a>
                                                <div class="pool-values" id="orgGrantDiv-2-2" style="display: none;">
                                                    <div class="fund-kv">
                                                        <span><small>Jeremy Pearl</small></span>
                                                        <span><small>$3,000.00</small></span>
                                                    </div>
                                                    <div class="fund-kv">
                                                        <span><small>Phil Sheehy</small></span>
                                                        <span><small>$2,000.00</small></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="chart-box mt-2">
                        <div class="title">Contributions</div>
                        @include('agency.agency-advisor.chart.contributions-bar-chart', ['contributionChartData' => $contributionChartData])
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                    <div class="chart-box mt-2">
                        <div class="title">Grants</div>
                       @include('agency.agency-advisor.chart.grants-bar-chart', ['grantsChartData' => $grantsChartData])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
