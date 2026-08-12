<?php
if (!isset($configReport)) $configReport = [];

$maxReportColumns = \App\Helpers\ReportData::MAX_REPORT_COLUMNS;
$minReportColumns = \App\Helpers\ReportData::MIN_REPORT_COLUMNS;
$typeList = \App\Helpers\ReportData::getReportTypeList();

?>
{{-- @extends ('admin.layouts.main') --}}
@extends (\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => 'Filter'.' '.$typeList[$type]])
    <div class="container form-wrapper form-last gn-form" >
        <div class="row">
            <div class="col-md-12">
                @include('errors.form-errors')


                <form method="GET" action="{{ route('view-report') }}" class="form-horizontal" id="id-filter-form">
                    <input type="hidden" name="report_type" value="{{ $type }}">
                    <input type="hidden" name="id" id="id_filter_id" value="{{ $configReport->id ?? '' }}">

                @include('agency.agency-advisor.reports._form-search-criteria')
                @include('agency.agency-advisor.reports._form-sorting-order')
                @include('agency.agency-advisor.reports._form-output-columns')

                <div id="filter-name-div" style="display: none;">
                    <hr>
                    <div class="form-group row mt-2">
                        <label for="id_filter_name" class="col-md-2 mt-1">Filter Name</label>
                        <div class="col-md-3">
                            <input type="text" name="filter_name" id="id_filter_name" class="form-control" value="{{ $configReport->filter_name ?? '' }}" onkeypress="return /^[a-z0-9. -]+$/i.test(event.key)" minlength="3" maxlength="16">
                        </div>
                        {{--<div class="offset-md-2 font-small pl-2 mt-1">Use only lowercase, numbers, dots, and hyphens (Eg. abc, abc-1, abc.1)</div>--}}
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="offset-md-1 col-md-3 mb-1">
                        <button type="submit" class="btn btn-accent w100" name="view_report" value="Y">View report</button>
                    </div>
                    <div class="col-md-3 mb-1">
                        @if(!isset($configReport->id))
                            <button type="button" class="btn btn-accent w100" name="save_current_filter_as" id="save_current_filter">Save current filter as</button>
                        @endif
                        <button type="submit" class="btn btn-accent w100" name="save_view_report" id="id_save_view_report">Save & View report</button>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var maxSelectCount = "{{$maxReportColumns}}";
            var minSelectCount = "{{$minReportColumns}}";
            var checkboxes = $('.cb-column-filter');

            checkboxes.change(function() {
                if (checkboxes.filter(':checked').length > maxSelectCount) {
                    this.checked = false;
                    alert('You can only select a maximum of ' + maxSelectCount + ' output columns.');
                }
            });

            $('#id-filter-form').submit(function(event) {
                var checked = checkboxes.filter(':checked').length;
                if (checked < minSelectCount) {
                    event.preventDefault();
                    alert('You must select at least ' + minSelectCount + ' output columns.');
                }
            });

            $('#save_current_filter').on('click', function() {
                $('#save_current_filter').hide();
                $('#filter-name-div').show();
                $("#id_save_view_report").show();
            });

            var filterId = $("#id_filter_id").val();
            if (filterId != '') {
                $('#filter-name-div').show();
//                $("#id_save_view_report").show();
            } else {
                $('#filter-name-div').hide();
                $("#id_save_view_report").hide();
            }
        });
    </script>

    <script>

        $(document).ready(function() {
            $('#id_calendar_div, input[type=radio][name=date_range]').show();
            if ($('#id_duration').is(':checked')) {
                $('#id_duration_div, #id_calendar_div').toggle();
            }

            $('input[type=radio][name=date_range]').change(function() {
                $('#id_duration_div, #id_calendar_div').toggle();
            });
        });
    </script>
    <script>
        $(function() {
            var format = 'MM-DD-YYYY';
            var formatDB = 'YYYY-MM-DD';

            var start = moment().subtract(10, 'years');
            var end = moment();
            var value = start.format(format) + ' - ' + end.format(format);

            var selectedStart = "{{$configReport->search_criteria->start_date ?? ''}}";
            var selectedEnd = "{{$configReport->search_criteria->end_date ?? ''}}";
            var defaultValue = selectedStart && selectedEnd ? moment(selectedStart, formatDB).format(format) + ' - ' + moment(selectedEnd, formatDB).format(format) : value;

            $('#id-date-range').val(defaultValue);

            $('#id-start-date').val(start.format(formatDB));
            $('#id-end-date').val(end.format(formatDB));
            $('input[id="id-date-range"]').daterangepicker({
                locale: {
                    format: format
                },
                opens: 'left',
                minYear: 2000,
                maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {
                //console.log("A new date selection was made: " + start.format(format) + ' to ' + end.format(format));
                $('#id-start-date').val(start.format(formatDB));
                $('#id-end-date').val(end.format(formatDB));
            });
        });
    </script>

@endsection
