
@include('errors.form-errors')

<div class="form-group row">
    <label for="email" class="col-md-8 col-form-label text-rightx pr-0">New Email Address</label>
    <div class="col-md-6">
        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
    </div>
</div>

<h3>Impact</h3>
Once your new email address is confirmed and saved:
<ul>
    <li>Your new email address will become your <b>username</b>.</li>
    <li>You must use your new email address (i.e. username) and password to log in into your account.</li>
    <li>You will receive all email communication to your new email address.</li>
</ul>
<h3>Process</h3>
<ul>
    <li>Fill in your new email address and submit request.</li>
    <li>We will email you a link on your new email address. The link will expire in 15 minutes.</li>
    <li>Click on the link to change your email address and follow instructions on the page.</li>
</ul>

<div class="form-group row">
    <div class="offset-md-3x col-md-3 mt-2">
        <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">Submit</button>
    </div>
</div>
