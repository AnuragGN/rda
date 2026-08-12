<?php
if (!isset($primary)) {
    $primary = true;
}
$myArray = \App\Helpers\Data::getDAFRegistrationDonorInfo();
?>

@if($primary)
    <div class="form-group row">
        <label for="id-prefix" class="col-md-3 col-form-label text-right">Prefix</label>
        <div class="col-md-3 pl-0">
            <input id="id_prefix" name="prefix" type="text" class="form-control" placeholder="">
        </div>
    </div>
@endif

<div class="form-group row account-name">
    <label for="id-first-name" class="col-md-3 col-form-label text-right">Name</label>
    <div class="col-md-3 pl-0">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'onkeypress' => "return /[a-z]/i.test(event.key)"]) !!}
    </div>
    <div class="col-md-3 pl-0">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'required', 'onkeypress' => "return /[a-z]/i.test(event.key)"]) !!}
    </div>
</div>

<div class="form-group row hide">
    <label for="id_suffix" class="col-md-3 col-form-label form-multi-line-label text-right">Suffix<br>(optional)</label>
    <div class="col-md-3 pl-0">
        <input id="id_suffix" name="suffix" type="text" class="form-control" placeholder="">
    </div>
</div>
<div class="form-group row hide">
    <label for="id_prefname" class="col-md-3 col-form-label text-right">Preferred Name</label>
    <div class="col-md-3 pl-0">
        <input id="id_prefname" name="prefname" type="text" class="form-control" placeholder="">
    </div>
</div>
@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_SSN, $myArray))
    <div class="form-group row">
        <label for="id-ssn" class="col-md-3 col-form-label text-right">SSN#</label>
        <div class="col-md-3 pl-0">
            {!! Form::text('ssn', null, ['class' => 'form-control','id' => 'id_ssn', 'required', 'onkeypress' => "return /[0-9]/i.test(event.key)"]) !!}
        </div>
    </div>
@endif
@if($primary || in_array(App\Helpers\Data::DAFR_DONOR_INFO_DOB, $myArray))
    <div class="form-group row">
        <label for="id-dob" class="col-md-3 col-3 col-form-label text-right">Date of Birth</label>
        <div class="col-md-3 pl-0">
            {!! Form::date('dob', null, ['class' => 'form-control', 'id' => 'id_dob', 'required']) !!}
        </div>
    </div>
@endif
<div class="form-group row">
    <label for="id-phone" class="col-md-3 col-form-label text-right">Day Phone*</label>
    <div class="col-md-3 pl-0">
        {!! Form::text('phone_number', null, ['class' => 'form-control js_phone_format', 'id' => 'id_phone']) !!}
    </div>

    <div class="col-md-3 pl-0">
        {!! Form::select('phone_type', $phoneTypes, null, ['class' => 'form-control', 'rows' => 2, 'required']) !!}
    </div>
</div>

<div class="form-group row">
    <label for="id_email" class="col-md-3 col-form-label text-right">Email</label>
    <div class="col-md-6 pl-0">
        {!! Form::email('email', null, ['class' => 'form-control','id' => 'id_email', 'required']) !!}
    </div>
</div>

