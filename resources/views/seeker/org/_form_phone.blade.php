<?php ?>

<input type="hidden" name="phone_type" value="{{$phone->phone_type}}" />
<input type="hidden" name="organization_id" value="{{$phone->organization_id}}" />
<input type="hidden" name="organization_phone_id" value="{{$phone->organization_phone_id}}" />

<div class="form-group row">
    {!! Form::label('phone_number', 'Phone #', ['class' => 'col-sm-3 col-3 col-form-label text-right pr-0']) !!}
    <div class="col-md-3 col-6 pr-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">+1</div>
            </div>
        	{!! Form::text('phone_number', null, ['id' => 'id_phone', 'class' => 'form-control']) !!}
        </div>
    </div>

    <div class="offset-md-3 col-3">
        {!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent w100']) !!}
    </div>
</div>
