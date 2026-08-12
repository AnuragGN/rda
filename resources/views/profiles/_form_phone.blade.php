<?php ?>

<input type="hidden" name="phone_type" value="{{$phone->phone_type}}" />
<input type="hidden" name="id" value="{{$phone->contact_phone_id}}" />

<div class="form-group row">
    <label for="phone_number" class="col-sm-3 col-3 col-form-label text-right pr-0">Phone #</label>
    <div class="col-md-3 col-6 pr-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">+1</div>
            </div>
            <input type="text" name="phone_number" id="id_phone" class="form-control" value="{{ old('phone_number', $phone->phone_number) }}">
        </div>
    </div>

    <div class="offset-md-3 col-3">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">Save</button>
</div>
