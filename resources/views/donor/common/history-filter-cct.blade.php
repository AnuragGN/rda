<?php
$startDate = request()->startDate;
$endDate = request()->endDate;
$funds = array_merge(['all' => 'All'], \App\Models\Fund::getSelectableForGrantRecommendation());
$statuses = \App\Helpers\Data::getGrantHistoryStatuses();
$interestAreas = \App\Models\GrantHistory::getInterestAreasSelectable();
?>

{!! Form::model($filter, ['method' => "GET", 'files' => false, 'id' => 'form-history-filter', 'class' => '' ]) !!}
<div class="row" id="statement-filter" style="display: none">
    <div class="col-12">
        <div class="filter-row">

            <div class="form-group row">
                {!! Form::label('fund', 'Fund Name', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
                <div class="col-sm-9">
                    {!! Form::select('fund_id', $funds, null, ['id' => 'id_fund_id', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('interest_area', 'Category', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
                <div class="col-sm-9">
                    {!! Form::select('interest_area', $interestAreas, null, ['id' => 'id_interest_areas', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right pr-0" for="inlineFormInputName2">Select Period</label>

                <div class="col-md-4">
                    <input type="text" id="id-date-range" name="dateRange" class="form-control"  value="01/01/2018 - 01/15/2018" />
                    <input id="id-start-date" name="startDate" type="hidden" value="1">
                    <input id="id-end-date" name="endDate" type="hidden" value="3">
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('status', 'Status', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
                <div class="col-sm-9">
                    {!! Form::select('status', $statuses, null, ['id' => 'id_fund_id', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('amount', 'Amount ($)', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
                <div class="col-sm-9">
                    <div style="display: flex">
                        {!! Form::number('amount_min', null, ['class' => 'form-control']) !!}

                        {{--<input type="text" id="amount-from" name="amount_min" class="form-control"  value="" />--}}
                        <div class="mr-2 ml-2 pt-1">to</div>
                        {!! Form::number('amount_max', null, ['class' => 'form-control']) !!}
                        {{--<input type="text" id="amount-to" name="amount_max" class="form-control"  value="" />--}}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-3 col-sm-3">
                    <button type="submit" class="btn btn-theme mb-2 js_on_submit_filter w100">Submit</button>
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
