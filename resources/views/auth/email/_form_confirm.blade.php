
@include('errors.form-errors')

<input type="hidden" name="token" value="{{$token}}"/>
<input type="hidden" name="email" value="{{$email}}"/>

<p>To change your email address to <b>{{$email}}</b>, enter your current password and submit request.</p>

<div class="form-group row">
    <label for="password" class="col-md-12 col-form-label text-rightx pr-0">Current Password</label>
    <div class="col-md-3">
        <div class="input-group">
            <input class="form-control" required="required" name="password" type="password" id="password">
        <div class="input-group-append">
            <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
                <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
                <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
            </div>
        </div>
        </div>
        {{-- {!! Form::text('password', null, ['class' => 'form-control', 'required' => 'required']) !!}--}}
    </div>
</div>

<h3>Impact</h3>
Once your new email address is confirmed and saved:
<ul>
    <li>Your new email address will become your <b>username</b>.</li>
    <li>You must use your new email address (i.e. username) and password to log in into your account.</li>
    <li>You will receive all email communication to your new email address.</li>
</ul>

<div class="form-group row">
    <div class="offset-md-3x col-md-3 mt-2">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">Submit</button>
    </div>
</div>

<script>
    function showPassword(item) {
        var targetId = $(item).data('target-id');
        var elem = $('#' + targetId);
        elem.parent().find(".fa-eye").show();
        elem.attr('type', 'text');
        $(item).hide();
    }
    function hidePassword(item) {
        var targetId = $(item).data('target-id');
        var elem = $('#' + targetId);
        elem.parent().find(".fa-eye-slash").show();
        elem.attr('type', 'password');
        $(item).hide();
    }
</script>
