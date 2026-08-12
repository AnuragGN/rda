@php
    $startDate = request()->startDate;
    $endDate   = request()->endDate;
@endphp

<form method="GET" id="form-history-filter">
<div class="row" id="statement-filter" style="display:none;">
    <div class="col-12">
        <div class="filter-row">
            <div class="row">

                @if ($orgCls)
                <div class="col-md-6">
                    <div class="row">
                        <label for="organization_id" class="col-sm-3 col-form-label">Grantee</label>
                    </div>
                    <div style="display:flex;">
                        <div class="mb-2 mr-sm-2" style="width:100%;">
                            <select name="organization_id" id="organization_id" class="form-control">
                                <option value="">All Grantees</option>
                                @foreach ($organizations as $id => $name)
                                    <option value="{{ $id }}" @selected($selectedId == $id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-md-6">
                    <div class="row">
                        <label class="col-sm-12 col-form-label" for="id-date-range">Select Period</label>
                    </div>
                    <div style="display:flex;">
                        <input type="text" id="id-date-range" name="dateRange"
                            class="form-control mb-2 mr-sm-2" value="" autocomplete="off" />
                        <input id="id-start-date" name="startDate" type="hidden" value="">
                        <input id="id-end-date"   name="endDate"   type="hidden" value="">
                        <button type="submit" class="btn btn-theme mb-2 js_on_submit_filter">Go</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</form>

<script>
    $('body').on('click', '.js_on_submit_filter', function () {
        $('#id-date-range').removeAttr('name');
    });

    $(function () {
        var format   = 'MM-DD-YYYY';
        var formatDB = 'YYYY-MM-DD';

        var start = moment().subtract(10, 'years');
        var end   = moment();

        var startDate = "{{ $startDate }}";
        var endDate   = "{{ $endDate }}";

        if (startDate && startDate.length === 10 && endDate && endDate.length === 10) {
            start = moment(startDate, 'YYYY-MM-DD');
            end   = moment(endDate,   'YYYY-MM-DD');
        }

        $('#id-date-range').val(start.format(format) + ' - ' + end.format(format));
        $('#id-start-date').val(start.format(formatDB));
        $('#id-end-date').val(end.format(formatDB));

        $('input[name="dateRange"]').daterangepicker({
            locale:   { format: format },
            opens:    'left',
            minYear:  2000,
            maxYear:  parseInt(moment().format('YYYY'), 10)
        }, function (start, end) {
            $('#id-start-date').val(start.format(formatDB));
            $('#id-end-date').val(end.format(formatDB));
        });
    });
</script>
