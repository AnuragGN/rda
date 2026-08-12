<?php
$startDate = request()->startDate;
$endDate = request()->endDate;
if (\App\Models\ClientInfo::isCCT()) {
    $funds = array_merge(['all' => 'All'], \App\Models\Fund::getSelectableForGrantRecommendation());
}
?>

{!! Form::model($filter, ['method' => "GET", 'files' => false, 'id' => 'form-history-filter', 'class' => '' ]) !!}
<div class="row" id="statement-filter" style="display: none">
    <div class="col-12">
        <div class="filter-row">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        {!! Form::label('fund', 'Funds', ['class' => 'col-sm-3 col-form-label']) !!}
                    </div>

                    <div style="display: flex">
                        <div class="mb-2 mr-sm-2" style="width: 100%">
                            {!! Form::select('fund_id', $funds, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="row">
                        <label class="col-sm-12 col-form-label" for="inlineFormInputName2">Select Period</label>
                    </div>

                    <div style="display: flex">
                        <input type="text" id="id-date-range" name="dateRange" class="form-control mb-2 mr-sm-2"  value="01/01/2018 - 01/15/2018" />
                        <input id="id-start-date" name="startDate" type="hidden" value="1">
                        <input id="id-end-date" name="endDate" type="hidden" value="3">
                        <button type="submit" class="btn btn-theme mb-2 js_on_submit_filter">Go</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
{!! Form::close() !!}

<script>
    $('body').on('click', '.js_on_submit_filter', function (e) {
        // e.preventDefault();
        $('#id-date-range').removeAttr('name');
        // return false;
    });

    $(function() {
        var format = 'MM-DD-YYYY';
        var formatDB = 'YYYY-MM-DD';

        var start = moment().subtract(10, 'years');
        var end = moment();

        var startDate = "{{ $startDate }}";
        var endDate = "{{ $endDate }}";
        if (startDate && startDate.length === 10 && endDate && endDate.length === 10) {
            start = moment(startDate, 'YYYY-MM-DD');
            end = moment(endDate, 'YYYY-MM-DD');
            console.log("Start date is : " + start.format(format));
            console.log("End date is : " + end.format(format));
        }

        var value = start.format(format) + ' - ' + end.format(format);
        console.log(value);
        $('#id-date-range').val(value);

        $('#id-start-date').val(start.format(formatDB));
        $('#id-end-date').val(end.format(formatDB));

        $('input[name="dateRange"]').daterangepicker({
            locale: {
                format: format
            },
            opens: 'left',
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
            // alert('date');
            console.log("A new date selection was made: " + start.format(format) + ' to ' + end.format(format));
            $('#id-start-date').val(start.format(formatDB));
            $('#id-end-date').val(end.format(formatDB));
        });
    });
</script>
