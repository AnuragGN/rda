
@include('errors.form-errors')

@if(\App\Models\ClientInfo::isJCF())
    <p class="font-small">
        Password must be <span class="fw600">8 or more characters</span> in length,
        including one <span class="fw600">uppercase</span>, one
        <span class="fw600">lowercase</span>,
        and one <span class="fw600">numeric digit</span>.
    </p>
@else
    <p class="font-small">Password must be at least <span class="fw600">8 characters long</span> and must have
        one <span class="fw600">uppercase character</span>,
        one <span class="fw600">lowercase character</span>,
        one <span class="fw600">special character</span> and
        one <span class="fw600">number</span>.
    </p>
@endif

<div class="form-group row">
    <label for="current_password" class="col-md-3 col-form-label text-right pr-0">Current Password</label>
    <div class="col-md-4">
        <div class="input-group">
            <input class="form-control" required="required" name="current_password" type="password" id="current_password">
            <div class="input-group-append">
                <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer; color: #666;">
                    <i class="fas fa-eye-slash" data-target-id="current_password" style="width: 20px;" onclick="showPassword(this)"></i>
                    <i class="fas fa-eye" data-target-id="current_password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="password" class="col-md-3 col-form-label text-right pr-0">New Password</label>
    <div class="col-md-4">
        <div class="input-group">
            <input class="form-control" required="required" name="password" type="password" id="password">
        <div class="input-group-append">
            <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
                <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
                <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="password_confirmation" class="col-md-3 col-form-label text-right pr-0">Confirm New Password</label>
    <div class="col-md-4">
        <div class="input-group">
            <input class="form-control" required="required" name="password_confirmation" type="password" id="password_confirmation">
        <div class="input-group-append">
            <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
                <i class="fas fa-eye-slash" data-target-id="password_confirmation" style="width: 20px;" onclick="showPassword(this)"></i>
                <i class="fas fa-eye" data-target-id="password_confirmation" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
            </div>
        </div>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="offset-md-3 col-md-4 mt-2">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">Update</button>
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
