

<div class="form-group row">
    <label for="id-address-one" class="col-sm-3 col-form-label text-right">Address Line 1</label>
    <div class="col-sm-6 pl-0">
        {!! Form::text("address_line_1", null, ['class' => 'form-control', 'required']) !!}
        {{--<input id="id_address_one" name="address_one" type="text" class="form-control" placeholder="" required="">--}}
    </div>
</div>

<div class="form-group row">
    <label for="id-address-two" class="col-sm-3 col-form-label text-right form-multi-line-label">Address Line 2<br>(optional)</label>
    <div class="col-sm-6 pl-0">
        {!! Form::text("address_line_2", null, ['class' => 'form-control']) !!}

    </div>
</div>

<div class="form-group row address-details">
    <label for="id_city" class="col-sm-3 col-form-label text-right">City</label>
    <div class="col-sm-2 pl-0 pr-0">
        {!! Form::text("city", null, ['class' => 'form-control', 'required']) !!}
        {{--<input id="id_city" name="city" type="text" class="form-control" placeholder="" required="">--}}
    </div>
    <div class="col-sm-2 pl-0 text-right">
        <label for="id_zip" class="col-form-label">ZIP</label>
    </div>
    <div class="col-sm-2 pl-0">
        {!! Form::text('zip', null, ['class' => 'form-control','id' => 'id_zip', 'required']) !!}
    </div>
</div>

<div class="form-group row address-details">
    <label for="id_state" class="col-sm-3 col-form-label text-right">State</label>
    <div class="col-sm-2 pl-0 pr-0">
        {!! Form::select('state', $states, null, ['class' => 'form-control', 'required']) !!}
        {{--<input id="id-state" name="ziip" type="text" class="form-control" placeholder="" required="">--}}
    </div>
    <div class="col-sm-2 pl-0 text-right">
        <label for="id_country" class="col-form-label text-right">Country</label>
    </div>
    <div class="col-sm-2 pl-0">
        {!! Form::select('country', $countries, 'USA', ['class' => 'form-control', 'required']) !!}
        {{--<input id="id-country" name="country" type="text" class="form-control" placeholder="" required="">--}}
    </div>
</div>
