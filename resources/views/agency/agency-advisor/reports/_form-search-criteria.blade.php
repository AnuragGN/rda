<?php
$contactFunds = \App\Models\Fund::getSelectableByContactId();
$reportDurationOptions = \App\Helpers\ReportManager::getReportDurationOptions();
$contactTypes = \App\Models\ContactType::getContactTypeList();
// $placeholderSearchNameInput = ($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GIFT_HISTORY) ? "Donor name" : 'Grantee';

$placeholderSearchNameInput = ($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GIFT_HISTORY) ? "All Funds " : 
                               (($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GRANT_HISTORY) ? "All Funds " :
                               (($type == \App\Helpers\ReportData::REPORT_TYPE_SERVICE_TICKET) ? "All Funds " :
                               (($type == \App\Helpers\ReportData::REPORT_TYPE_CLIENT) ? "All Funds" : ""))
);



?>
<h4 class="row page-title uppercase mt-0">Search Criteria</h4>

{{-- Fund by name --}}
{{-- <div class="form-group row mt-2">
    {!! Form::label('id_name', 'Fund Name', ['class'=>'col-md-2 col-form-label']) !!}
    <div class="col-md-3">
        {!! Form::text('fund_id', $configReport->search_criteria->fund_id ?? null, ['class' => "form-control", 'onkeypress' => "return /^[a-z0-9. -]+$/i.test(event.key)", 'id' => "id_name", 'maxlength'=>'16', 'placeholder' => $placeholderSearchNameInput]) !!}
    </div>
</div> --}}

{{-- Fund Dropdown --}}
@php
    $selectedFundId = request('fund_id', $configReport->search_criteria->fund_id ?? '');
@endphp
<div class="form-group row mt-2">
    <label for="fund_id" class="col-md-2 col-form-label">Fund Name</label>
    <div class="col-md-3">
        <select name="fund_id" id="fund_id" class="form-control">
            <option value="">{{ $placeholderSearchNameInput }}</option>
            @foreach($contactFunds as $key => $value)
                <option value="{{ $key }}"{{ $selectedFundId == $key ? ' selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Fund multiple Dropdown --}}
{{-- <div class="form-group row mt-2">
    {!! Form::label('id_name', 'Fund Name', ['class'=>'col-md-2 col-form-label']) !!}
    <div class="col-md-3">
        @if($type == \App\Helpers\ReportData::REPORT_TYPE_CLIENT)
            {{ Form::select('fund_id[]', $contactFunds, $configReport->search_criteria->fund_id ?? '', ['class' => 'form-control', 'placeholder' => $placeholderSearchNameInput, 'multiple' => 'multiple', 'onchange' => "if(this.value == '') { this.selectedIndex = -1; }"]) }}
        @else
            {{ Form::select('fund_id', $contactFunds, $configReport->search_criteria->fund_id ?? '', ['class' => 'form-control', 'placeholder' => $placeholderSearchNameInput]) }}
        @endif
    </div>
</div> --}}




@if($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GIFT_HISTORY || $type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GRANT_HISTORY || $type == \App\Helpers\ReportData::REPORT_TYPE_SERVICE_TICKET) 
    @php
        $selectedDateRange = request('date_range', $configReport->search_criteria->date_range ?? 'calendar');
    @endphp
    <div class="form-group row">
        <label for="creation_date" class="col-md-2 col-form-label">Date Range</label>
        <div class="col-md-3 mt-1">
            <div class="form-check form-check-inline">
                <input type="radio" name="date_range" value="calendar" class="form-check-input" id="id_calendar"{{ $selectedDateRange === 'calendar' ? ' checked' : '' }}>
                <label class="form-check-label" for="id_calendar">Calendar</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="date_range" value="duration" class="form-check-input" id="id_duration"{{ $selectedDateRange === 'duration' ? ' checked' : '' }}>
                <label class="form-check-label" for="id_duration">Duration</label>
            </div>
        </div>

        <div class="col-sm-3 calendar" id="id_calendar_div" style="display: none;">
            <input type="text" id="id-date-range" class="form-control"/>
            <input id="id-start-date" name="start_date" type="hidden" value="{{$configReport->search_criteria->start_date ?? ''}}">
            <input id="id-end-date" name="end_date" type="hidden" value="{{$configReport->search_criteria->end_date ?? ''}}">
        </div>
        <div class="col-sm-3 calendar" id="id_duration_div" style="display: none;">
            @php $selectedDuration = request('duration', $configReport->search_criteria->duration ?? ''); @endphp
            <select name="duration" class="form-control">
                @foreach($reportDurationOptions as $key => $label)
                    <option value="{{ $key }}"{{ $selectedDuration == $key ? ' selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif


{{-- @if($type == \App\Helpers\ReportData::REPORT_TYPE_CLIENT)
    <div class="form-group row">
        {!! Form::label('contact_type', 'Contact Type', ['class'=>'col-md-2 col-form-label pr-0']) !!}
        <div class="col-md-3 ">
            {!! Form::select('contact_type', $contactTypes, $configReport->search_criteria->contact_type ?? '', ['class'=>'form-control', 'placeholder' => 'All'])!!}
        </div>
    </div>
@endif --}}

{{-- @if($type == \App\Helpers\ReportData::REPORT_TYPE_SERVICE_TICKET)
    <div class="form-group row mt-2">
        {!! Form::label('id_status', 'Status', ['class'=>'col-md-2 col-form-label']) !!}
        <div class="col-md-3">
            {!!Form::text('search_status', $configReport->search_criteria->search_status ?? '', ['class' => "form-control", 'onkeypress' => "return /^[a-z0-9. -]+$/i.test(event.key)", 'id' => "id_status", 'maxlength'=>'16'])!!}
        </div>
    </div>
@endif --}}

{{-- <div class="form-group row">
    {!! Form::label('updated_date', 'Updated Date Range', ['class'=>'col-md-2 col-form-label']) !!}
    <div class="col-md-3 mt-1">
        <div class="form-check form-check-inline">
            {!! Form::radio('updated_date_range', 'calendar', isset($configReport->search_criteria->updated_date_range) && $configReport->search_criteria->updated_date_range === 'calendar' ?? '', ['class' => 'form-check-input', 'id' => "id_updated_calendar", "checked"]) !!}
            <label class="form-check-label" for="id_updated_calendar">Calendar</label>
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio('updated_date_range', 'duration', isset($configReport->search_criteria->updated_date_range) && $configReport->search_criteria->updated_date_range === 'duration' ?? '', ['class' => 'form-check-input', 'id' => "id_updated_duration"]) !!}
            <label class="form-check-label" for="id_updated_duration">Duration</label>
        </div>
    </div>

    <div class="col-sm-3 updated_calendar" id="id_updated_calendar_div" style="display: none;">
        <input type="text" id="id-updated-date-range" class="form-control"/>
        <input id="id-updated-start-date" name="updated_start_date" type="hidden" value="{{$configReport->search_criteria->updated_start_date ?? ''}}">
        <input id="id-updated-end-date" name="updated_end_date" type="hidden" value="{{$configReport->search_criteria->updated_end_date ?? ''}}">
    </div>
    <div class="col-sm-3 updated_calendar" id="id_updated_duration_div" style="display: none;">
        {{ Form::select('updated_duration', $reportDurationOptions, $configReport->search_criteria->updated_duration ?? '', ['class' => 'form-control']) }}
    </div>
</div> --}}

{{-- @if($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GIFT_HISTORY)
    <div class="form-group row">
        {!! Form::label('contact_type', 'Contact Type', ['class'=>'col-md-2 col-form-label pr-0']) !!}
        <div class="col-md-3 ">
            {!! Form::select('contact_type', $contactTypes, $configReport->search_criteria->contact_type ?? '', ['class'=>'form-control', 'placeholder' => 'All'])!!}
        </div>
    </div>
@endif --}}

{{-- @if($type == \App\Helpers\ReportData::REPORT_TYPE_FUND_GRANT_HISTORY)
    <div class="form-group row">
        {!! Form::label('id-sync', 'Sync', ['class'=>'col-md-2 col-form-label pr-0']) !!}
        <div class="col-md-3 col-sm-3">
            {!! Form::select('sync', [null => '', 'Y' => 'Yes', 'N' => 'No'], $configReport->search_criteria->sync ?? '', ['class' => 'form-control'])!!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('id-visible', 'Visible', ['class'=>'col-md-2 col-form-label pr-0']) !!}
        <div class="col-md-3 col-sm-3">
            {!! Form::select('visible', [null => '', 'Y' => 'Yes', 'N' => 'No'], $configReport->search_criteria->visible ?? '', ['class' => 'form-control'])!!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('id-allow_recommendation', 'Allow Recommendation', ['class'=>'col-md-2 col-form-label pr-0']) !!}
        <div class="col-md-3 col-sm-3">
            {!! Form::select('allow_recommendation', [null => '', 'Y' => 'Yes', 'N' => 'No'], $configReport->search_criteria->allow_recommendation ?? '', ['class' => 'form-control'])!!}
        </div>
    </div>
@endif --}}

<script>

    $(document).ready(function() {
        $('#id_updated_calendar_div, input[type=radio][name=updated_date_range]').show();
        if ($('#id_updated_duration').is(':checked')) {
            $('#id_updated_duration_div, #id_updated_calendar_div').toggle();
        }

        $('input[type=radio][name=updated_date_range]').change(function() {
            $('#id_updated_duration_div, #id_updated_calendar_div').toggle();
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

        var selectedStart = "{{$configReport->search_criteria->updated_start_date ?? ''}}";
        var selectedEnd = "{{$configReport->search_criteria->updated_end_date ?? ''}}";
        var defaultValue = selectedStart && selectedEnd ? moment(selectedStart, formatDB).format(format) + ' - ' + moment(selectedEnd, formatDB).format(format) : value;

        $('#id-updated-date-range').val(defaultValue);

        $('#id-updated-start-date').val(start.format(formatDB));
        $('#id-updated-end-date').val(end.format(formatDB));
        $('input[id="id-updated-date-range"]').daterangepicker({
            locale: {
                format: format
            },
            opens: 'left',
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
            $('#id-updated-start-date').val(start.format(formatDB));
            $('#id-updated-end-date').val(end.format(formatDB));
        });
    });
</script>

{{-- <script>
    $(document).ready(function(){
        $('#id_name optgroup[label="0"]').hide(); // Hide the optgroup with label "0"
    });
</script> --}}
