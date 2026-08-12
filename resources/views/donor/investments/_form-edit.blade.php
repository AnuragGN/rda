<?php
$maxSelectable = 2;
if (\App\Models\ClientInfo::isNIF()) {
    $maxSelectable = 6;
} else if (\App\Models\ClientInfo::isHGA()) {
    $maxSelectable = 2;
}
?>

@if(count($selector) > 1)
    <div class="row">
        <div class="col-md-5">
            {!! Form::select('fund_id', $selector, $id, ['class' => 'form-control ', 'id' => 'id_fund_selector']) !!}
            <br>
        </div>
    </div>
@endif

<div class="form-make-grant gn-form">
    {!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['save-investments'], 'id' => 'id_investments_form']) !!}

    @include('errors.form-errors')

    {!!  Form::hidden('fund_id', $id, []) !!}

    <h5 class="page-subtitle mt-2" style="justify-content: normal;">
        <div class="col-md-7">{{\App\Models\Investments::poolTitle()}}</div>
        <div class="col-md-2">Current Allocation %</div>
        <div class="col-md-2">New Allocation %</div>
    </h5>

    <p id="id_err_max_selected" style="color: #ff0000; font-weight: bold; display: none">
        You can select a maximum of {{$maxSelectable}} investment options.
    </p>

    @foreach($allocations as $i => $alloc)
        @if($alloc->pool_id != 'FRMM')
            <div class="row form-group ">
                <div class="col-md-7">{{$alloc->pool_name}}</div>
                <div class="col-md-2">{{$alloc->allocation}}</div>
                {!! Form::number('allocations[' . $alloc->pool_id . ']', $alloc->requested_allocation,
                ['class' => 'form-control col-md-2', 'id' => 'id_alloc_' . $i, 'min' => 0]) !!}
            </div>
        @endif
    @endforeach

    @foreach($allocations as $i => $alloc)
        @if($alloc->pool_id == 'FRMM')
            <div class="row form-group ">
                <div class="col-md-7">{{$alloc->pool_name}}</div>
                <div class="col-md-2">{{$alloc->allocation}}</div>
                <input id="id_mmp" class="form-control col-md-2" readonly name={{'allocations[' . $alloc->pool_id . ']'}}>
            </div>
        @endif
    @endforeach

    {{--<hr>--}}
    <div class="form-group row">
        <div class="col-md-11 mb-2" style="text-align: right">
            <span style="font-size: 110%" id="id_alloc_status"></span>
        </div>
        <div class="offset-md-9 col-md-2 pl-0 pr-0">
            {!! Form::submit('Submit', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent w100']) !!}
        </div>
    </div>

    {!! Form::close() !!}
</div>

<script>
    $('#id_fund_selector').change(function(){
        var data= $(this).val();
        window.location.href = '/m/donor/investments/' + data + '/edit';
    });

    $(':input[type="number"]').keypress(function(event) {
        // console.log('event',  event.which);
        if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
            // $("#errmsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
    }).click(function(){
        var value = parseInt($(this).val());
        if (value == 0 || value < 1) $(this).val('');
        console.log('clicked!', value);
    }).blur(function(){
        var value = parseInt($(this).val());
        if (isNaN(value)) $(this).val(0);
        // console.log('blur!', value);
    }).change(function() {
        updateAllocStatus();
    });

    $(function(){
        updateAllocStatus();
    });

    function updateAllocStatus(){

        var errorMesg = $('#id_err_max_selected');
        errorMesg.hide();

        var sum = 0;
        var selected = 0;
        for(var i=0; i<7; i++) {
            var allocationItem = $('#id_alloc_' + i);
            if (allocationItem == null) console.log("Null for ", i);
            var value = parseInt(allocationItem.val());
            if(isNaN(value)) {
                value = 0;
                allocationItem.val(0);
            }
            if (value > 0) selected++;
            sum += value;
        }

        var mmpValue = 100 - sum;
        var mmpItem = $('#id_mmp');
        mmpItem.val(100 - sum);

        if (sum > 100) {
            mmpItem.css({ 'color': '#ff0000', 'font-weight' : 'bold'});
        } else {
            mmpItem.css({ 'color': '#000', 'font-weight' : 'normal'});
        }

        // console.log('selected : ', selected);

        // console.log('SUM = ', sum);
        var item = $('#id_alloc_status');
        if (sum == 100) {
            // item.css({ 'color': '#000000'});
            item.removeClass('inv-total-err');
            item.addClass('inv-total-msg');
            item.html('Total allocation = 100%');
            $(':input[type="submit"]').prop('disabled', false);
        } else if ( sum < 100 ) {
            var left = 100 - sum;
            // item.css({ 'color': '#000000'});
            item.removeClass('inv-total-err');
            item.addClass('inv-total-msg');
            item.html('Amount left to invest ' + left + '%');
            $(':input[type="submit"]').prop('disabled', false);
        } else {
            // item.css({ 'color': '#ff0000'});
            item.removeClass('inv-total-msg');
            item.addClass('inv-total-err');
            item.html('Total allocation cannot be more than 100%');
            $(':input[type="submit"]').prop('disabled', true);
        }

        var maxSelectable = "{{$maxSelectable}}";

        if (selected > maxSelectable) {
            errorMesg.show();
            $(':input[type="submit"]').prop('disabled', true);
        }

    }
</script>
