<?php
$states = \App\Models\State::getCodeListUSA();
$countries = \App\Models\Country::getListUSAOnly(); // getList();
?>

{{--Address 1:--}} {{--P.O. Box 19075--}}
{{--Address 2:--}} {{--Atherton Drive--}}
{{--City:--}} {{--Newark--}}
{{--State:--}}
{{--Zip Code:--}} {{--07195-0078--}}
{{--Country:--}}

<input type="hidden" name="address_type" value="{{$address->address_type}}" />
<input type="hidden" name="organization_id" value="{{$org->organization_id}}" />
<input type="hidden" name="organization_address_id" value="{{$address->organization_address_id}}" />

<div class="form-group row pt-2-md">
    {!! Form::label('address_1', 'Address Line 1', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-8">
        {!! Form::text('address_1', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('address_2', 'Address Line 2', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-8">
        {!! Form::text('address_2', null, ['class' => 'form-control', 'placeholder' => 'optional']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('city', 'City', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-5">
        {!! Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('state', 'State', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-5">
        {!! Form::select('state', $states, '', ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('zip', 'ZIP', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-2 col-4">
        {!! Form::text('zip', null, ['id' => 'id_zip', 'class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('country', 'Country', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-5">
        {!! Form::select('country', $countries, 'USA', ['class' => 'form-control', 'disabled2']) !!}
    </div>
</div>

<hr>

<div class="form-group row">
    <div class="offset-md-3 col-md-5">
        {!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent w100']) !!}
    </div>
</div>
