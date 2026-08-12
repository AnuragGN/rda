{{--NIF Specific--}}

<?php
$dedicationTypes = [
        '' => 'None',
        'In Honor of' => 'In Honor of',
        'In Memory of' => 'In Memory of',
        'In Recognition of' => 'In Recognition of',
        'With thanks to' => 'With thanks to',
];
?>

<div class="form-group row">
    {!! Form::label('purpose_type', 'Grant Purpose', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-9 form-control" style="border: none; background: none;">
        <div class="form-check form-check-inline">
            {!! Form::radio('purpose_type', 'general', null, ['class' => 'form-check-input', 'id' => "id_purpose_general", "onclick" => "onGeneralSupport(this);"]) !!}
            <label class="form-check-label font-small" for="id_purpose_general">General Support</label>
        </div>
        <div class="form-check form-check-inline">
            {!! Form::radio('purpose_type', 'special', null, ['class' => 'form-check-input', 'id' => "id_purpose_special", "onclick" => "onSpecialPurpose(this);"]) !!}
            <label class="form-check-label font-small" for="id_purpose_special">Special Purpose</label>
        </div>
    </div>
</div>

{{-- This becomes visible when user selected purpose is "Special Purpose" --}}
<div class="form-group row" id="id_grant_purpose_container" style="display: none;">
    <div class="offset-md-3  col-sm-9">
        {!! Form::textarea('grant_purpose', null, ['class' => 'form-control', 'rows' => 2, 'id' => 'id_grant_purpose', 'placeholder' => 'Special grant purpose']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('dedication_type', 'Grant Dedication', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::select('dedication_type', $dedicationTypes, $model->dedication_type, ['class' => 'form-control', 'onchange'=> "onChangeDedication(this)"]) !!}
    </div>
</div>

{{-- This become visible when 'grant dedication' is selected --}}
<div class="form-group row" id="id_grant_dedication_container" style="display: none;">
    <div class="offset-md-3  col-sm-9">
        {!! Form::textarea('grant_dedication', null, ['class' => 'form-control', 'rows' => 2, 'id' => 'id_grant_dedication', 'placeholder' => 'Grant dedication']) !!}
    </div>
</div>

<div class="form-group row" style="margin-bottom: 0">
    {!! Form::label('notes', 'Notes', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2]) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('anonymous', 'I wish to remain anonymous', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x']) !!}
    </div>
</div>

<script>
    $(function(){
        // handle radio option
        var selectedPurpose = $("input[name='purpose_type']:checked").val();
        if (selectedPurpose == 'special') {
            $('#id_grant_purpose_container').show(400);
            $('#id_grant_purpose').prop('required', true);
        }

        // handle select option
        var selectedDedication = $('#dedication_type').find(":selected").val();
        if (selectedDedication && selectedDedication != '') {
            $('#id_grant_dedication_container').show(400);
            $('#id_grant_dedication').prop('required', true);
        }
    });

    function onGeneralSupport(item){
        $('#id_grant_purpose_container').hide(400);
        $('#id_grant_purpose').prop('required', false);
    }
    function onSpecialPurpose(item){
        $('#id_grant_purpose_container').show(400);
        $('#id_grant_purpose').prop('required', true);
    }
    function onChangeDedication(item) {
        console.log('' + item.value);
        var container = $('#id_grant_dedication_container');
        var containerInput = $('#id_grant_dedication');
        if (item.value == null || item.value == '') {
            container.hide(400);
            containerInput.prop('required', false);
        } else {
            container.show(400);
            containerInput.prop('required', true);
        }
    }
</script>
