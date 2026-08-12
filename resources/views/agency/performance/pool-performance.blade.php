<?php
$pageSubtitle = null;
$model = \App\Models\GhPerformance::where(['account_id' => $accountId])->first();
if ($model) {
    $from = \App\Helpers\GnUtils::customDate($model->begin_date);
    $to = \App\Helpers\GnUtils::customDate($model->end_date);
    $pageSubtitle = 'As of ' . $to;
}
?>

@include('common.page-header', ['pageTitle' => 'Pool Performance', 'pageSubtitle' => $pageSubtitle])

<section class="content">
    <div class="container">

        <div class="row">
            <div class="col-xl-12 col-lg-12" style="max-width: 840px">

                @include('agency.performance.performance-tabs')

                <div class="form-wrapper">

                    <div class="page-title mt-0" style="align-items: center;">
                        <h2>{{$segment->segment_label}}</h2>
                        @if(\App\Models\PerformanceData::performanceFileExists($accountId))
                            <a href="javascript:void(0);"
                               data-message="Download the performance PDF file?"
                               data-href="{{\App\Models\PerformanceData::performanceFileUrl($accountId, 'pool')}}"
                               class="js_confirm_file_download btn btn-sm btn-accent"
                               title="Download Performance Report">
                                Download <i class="fas fa-file-download"></i>
                            </a>
                        @endif
                    </div>

                    @include('agency.performance.composition-h')

                    @include('agency.performance.returns-summary')

                </div>
                <div class="form-wrapper form-last">
                    @include('agency.performance.footer', ['forFund' => false])
                </div>
            </div>
        </div>

    </div>
</section>
