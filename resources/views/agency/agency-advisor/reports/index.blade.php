<?php

$typeList = \App\Helpers\ReportData::getReportTypeList();

?>

@extends (\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => 'Reports'])
    <div class="container form-wrapper form-last" >
        <div class="row">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="row page-title uppercase mt-0">Report Types</h4>

                        @foreach($reportTypeList as $key => $val)
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{route('report-filter', ['type' => $key])}}" > {{$val}}</a> 
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <h4 class="row page-title uppercase">Saved Report Filters</h4>
                    </div>
                    @foreach($configReports as $filter)
                        <div class="col-md-12">
                            <a href="{{route('report-config', ['type' => $filter->report_type, 'id' => $filter->id])}}" >{{$typeList[$filter->report_type].' '.$filter->filter_name}}</a> 
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection