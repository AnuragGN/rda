<?php
$states = \App\Models\State::getCodeListUSA();
$displayOboInfo = $model['show_advisor_address'] ? 'inherit' : 'none';
?>

<div class="row mt-3 mb-2">
    <div class="offset-md-3  col-sm-9">
        <span>Please edit to reflect how you want the recommended name(s) to appear on the award letter:</span>
    </div>
</div>

    <div class="form-group row">
        {!! Form::label('from_name', 'Name(s)', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
        <div class="col-md-9">
            <input class="form-control" value="{{$model['from_name']}}" id="id_address_1" minlength="3" maxlength="32" name="from_name" type="text">
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-md-3 col-md-9 xs-mt-2">
            <div class="form-check form-check-inline">
                {!! Form::checkbox('show_advisor_address', null, $model['show_advisor_address'], ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'show_advisor_address', "onclick" => "onShowAdvisorAddress(this)"]) !!}
                {!! Form::label('show_advisor_address', 'Show Advisor Address', ['class' => 'form-check-label font-small fw600', 'for' => 'show_advisor_address']) !!}
            </div>
        </div>
    </div>

    <div id="id_obo_info" style="display: {{$displayOboInfo}};">
        <div class="form-group row">
            {!! Form::label('from_address1', 'Address Line 1', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-9">
                <input class="form-control" value="{{$model['from_address1']}}" id="id_address_1" minlength="3" maxlength="32" name="from_address1" type="text" autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('from_address2', 'Address Line 2', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-9">
                <input class="form-control" value="{{$model['from_address2']}}" id="id_address_2" minlength="3" maxlength="32" name="from_address2" type="text" autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('from_city', 'City', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                <input class="form-control" value="{{$model['from_city']}}" id="id_city" onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)" minlength="2" maxlength="32" name="from_city" type="text">
            </div>
            {!! Form::label('from_zip', 'Zip', ['class' => 'col-md-2 col-form-label text-right pr-0']) !!}
            <div class="col-md-3">
                <input class="form-control" value="{{$model['from_zip']}}" id="id_zip" onkeypress="return /[0-9]/i.test(event.key)" minlength="5" maxlength="5" name="from_zip" type="text">
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('from_state', 'State', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                {!! Form::select('from_state', $states, $model['from_state'], ['class' => 'form-control', 'id'=>'id_state']) !!}
            </div>
        </div>
    </div>

<script>
    function onShowAdvisorAddress(item) {
        var oboView = $('#id_obo_info');
        if (item.checked) {
            oboView.show(500);
        } else {
            oboView.hide(500);
        }
    }
</script>