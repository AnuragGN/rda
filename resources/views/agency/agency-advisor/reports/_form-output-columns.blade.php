<?php
$columns = \App\Helpers\ReportManager::getOutputColumnsByReportType($type);
$selectedOutputColumns = request('output_columns');
if (is_null($selectedOutputColumns)) {
    if (isset($configReport->output_columns)) {
        if (is_array($configReport->output_columns)) {
            $selectedOutputColumns = $configReport->output_columns;
        } elseif (is_object($configReport->output_columns)) {
            $selectedOutputColumns = array_keys((array) $configReport->output_columns);
        } else {
            $selectedOutputColumns = [];
        }
    } else {
        $selectedOutputColumns = [];
    }
}
if (!is_array($selectedOutputColumns)) {
    $selectedOutputColumns = [];
}
?>
<h4 class="row page-title uppercase">Output Columns</h4>
<div class="row">
    <span class="col-xl-12 font-small1 mb-2">
        {{--Please select at least {{$minReportColumns}} and up to {{$maxReportColumns}} columns for your report.--}}
        {{--Please select at least {{$minReportColumns}} column for your report.--}}

    </span>
</div>
<div class="row">
    @foreach ($columns as $key => $val)
        <span class="col-sm-3 mb-1">
            <input type="checkbox" name="output_columns[]" value="{{ $key }}" class="cb-column-filter mr-1" id="{{ $key }}"{{ in_array($key, $selectedOutputColumns) ? ' checked' : '' }}>
            <label class="form-check-label" for="{{ $key }}">{{ $val }}</label>
        </span>
    @endforeach
</div>