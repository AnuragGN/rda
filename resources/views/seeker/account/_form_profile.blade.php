<?php
$prefixes = \App\Models\Prefix::getSelectable();
$suffixes = \App\Models\Suffix::getSelectable();
$emailAddress = $contact->getEmailAddressAttribute();
$primaryPhone = $contact->getPrimaryPhoneNumber();
if (!$primaryPhone) {
	$primaryPhone = '';
}
?>

{!! Form::model($contact, ['method' => "POST", 'files' => false, 'route' => ['gs-account-profile-save'], 'id' => 'form-profile', 'class' => 'gs-form clearfix' ]) !!}
{!!  Form::hidden('contact_id', null, ['id' => 'profile-contact-id']) !!}

	<div class="card-body">
	    <div class="row">
		    <div class="col-8">
				<div class="form-group row">
					{!! Form::label('state', 'Prefix', ['class' => 'col-sm-4 col-form-label text-right']) !!}
				    <div class="col-sm-5">
				    	{!! Form::select('prefix', $prefixes, null, ['class' => 'form-control']) !!}
				    </div>
				</div>
				<div class="form-group row">
					{!! Form::label('title', 'Title', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
<!-- 				<div class="form-group row">
					{!! Form::label('executive_director', 'Default Contact?', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						<input type="checkbox" name="executive_director" value="Y">
					</div>
				</div> -->
				<div class="form-group row">
					{!! Form::label('first_name', 'First Name', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('middle_name', 'Middle Name', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('middle_name', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('suffix1', 'Suffix 1', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-5">
						{!! Form::select('suffix1', $suffixes, null, ['class' => 'form-control']) !!}
					</div>
				</div>
<!-- 				<div class="form-group row">
					{!! Form::label('suffix2', 'Suffix 2', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-5">
					  	<select name="suffix2" class="form-control">
					      <option value=""></option>
					      <option value="CPA">CPA</option>
					    </select>
					</div>
				</div> -->
				<div class="form-group row">
					{!! Form::label('preferred_email', 'Email Address', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::email('preferred_email', $emailAddress, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row mb-5">
					{!! Form::label('phone', 'Direct Phone Number', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-8">
						<div class="input-group">
							<div class="input-group-prepend">
							    <span class="input-group-text">+1</span>
							</div>
							{!! Form::text('phone', $primaryPhone, ['class' => 'form-control js_phone_format', 'id' => 'id_phone', 'required' => 'required']) !!}
						</div>
					</div>
				</div>

<!-- 				<div class="form-group row">
					{!! Form::label('username', 'Username', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('username', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div> -->

				<div class="form-group row">
					{!! Form::label('password', 'Password', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::password('password', ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row mb-5">
					{!! Form::label('password_confirm', 'Confirm Password', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-8">
						{!! Form::password('password_confirm', ['class' => 'form-control']) !!}
					</div>
				</div>
	        </div>
	        <div class="clearfix"></div>
	    </div>
</div>
<div class="card-footer">
	{!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent']) !!}
    <a href="{{route('gs-org-staff-management')}}" class="btn btn-default float-right">
        Cancel
    </a>
</div>
{!! Form::close() !!}

<script type="text/javascript">
	$(document).ready(function() {

		$(document)
		.on('click', '#id_save_btn', function(e) {
		    var isValid = false;
		    
		    var contactId = $('#profile-contact-id').val();
		    var title = $('#title').val().trim();
		    var firstName = $('#first_name').val().trim();
		    var lastName = $('#last_name').val().trim();
		    var preferredEmail = $('#preferred_email').val();
		    var phoneNum = $('#id_phone').val();
		    var password = $('#password').val();
		    var confirmPassword = $('#password_confirm').val();

		    var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		    var errorMEssage = '';

		    if (title == '') {
		    	errorMEssage = 'Title is required';
		    	$('#title').focus();
		    } else if (firstName == '') {
		    	errorMEssage = 'First Name is required';
		    	$('#first_name').focus();
		    } else if (lastName == '') {
		    	errorMEssage = 'Last Name is required';
		    	$('#last_name').focus();
		    } else if (preferredEmail == '') {
		    	errorMEssage = 'Email is required';
		    	$('#preferred_email').focus();
		    } else if (preferredEmail != '' && !emailRegex.test(preferredEmail)) {
		    	errorMEssage = 'Invalid Email format';
		    	$('#preferred_email').focus();
		    } else if (phoneNum == '') {
		    	errorMEssage = 'Phone Number is required';
		    	$('#preferred_email').focus();
		    } else {
		    	if (contactId) {
			    	// edit profile case
			    	if (password.trim() != '' && (password !== confirmPassword)) {
			    		errorMEssage = 'Password and confirm password does not match';
			    	}
			    } else {
			    	// create profile case
			    	if (password.trim() == '') {
			    		errorMEssage = 'Password is required';
			    	} else if (password !== confirmPassword) {
			    		errorMEssage = 'Password and confirm password does not match';
			    	}
			    }
		    } 

		    if (errorMEssage) {
		    	$(document).Toasts('create', {
		        	class: 'bg-danger',
		        	autohide: true,
		        	title: 'Error',
		        	delay: 2500,
		        	body: errorMEssage
		      	})
		    } else {
		    	isValid = true;
		    }

		    if(!isValid) {
		    	console.log('not submit');
		      e.preventDefault(); //prevent the default action
		    }
		});

	});
</script>