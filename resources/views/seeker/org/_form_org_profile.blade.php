<?php
$states = \App\Models\State::getCodeListUSA();
?>

{!! Form::model($org, ['method' => "POST", 'files' => false, 'id' => 'form-organization-profile', 'class' => 'form-horizontal' ]) !!}
@include('errors.form-errors')
	<div class="card-body">

		<div class="form-group row">
			<div class="col-12">
				<h5 class="card-title">Provide your organization's primary contact information below.</h5>
			</div>
		</div>

		<div class="row">
			<div class="col-12 offset-sm-3 offset-md-0 col-sm-9 col-lg-4 col-xl-4 order-2 order-lg-2 order-xl-2 col-lr-15">
				<div class="card text-center bg-secondary text-white">
					<div class="card-body">
						<p class="card-text text-uppercase mb-1">Upload Logo</p>
						<small class="card-text">(Preffered width=150px)</small>
						<input type="file" class="form-control-file" id="exampleFormControlFile1">

						<p class="card-text text-uppercase mb-1 mt-3">Upload Image</p>
						<small class="card-text">(Preffered width=600px, height=400px)</small>
						<input type="file" class="form-control-file" id="exampleFormControlFile1">

					</div>
				</div>
			</div>

			<div class="col-12 col-lg-8 col-xl-6 order-1 order-lg-1 order-xl-1 col-lr-15">
				<div class="form-group row">
					{!! Form::label('name', 'Organization', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('address1', 'Address Line 1', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('address1', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('address2', 'Address Line 2', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('address2', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('city', 'City', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('state', 'State', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::select('state', $states, '', ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('zip', 'Zip Code', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-6">
						{!! Form::text('zip', null, ['class' => 'form-control', 'maxlength' => '10', 'id' => 'id_zip', 'required' => 'required']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('phone', 'Phone Number', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-6">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">+1</span>
							</div>
							{!! Form::text('phone', null, ['class' => 'form-control js_phone_format', 'id' => 'id_phone']) !!}
						</div>
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('preferred_email', 'Email Address', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('preferred_email', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('web_site', 'Website', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('web_site', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('annual_operating', 'Annual Operating Revenue', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
					<div class="col-sm-9">
						{!! Form::text('annual_operating', null, ['class' => 'form-control']) !!}
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="card-footer">
		{!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent']) !!}
		{!! Form::submit('Save/Continue', ['name' => 'save-continue', 'class' => 'btn btn-accent ml-2']) !!}
		<button type="submit" class="btn btn-default float-right">Cancel</button>
	</div>
{!! Form::close() !!}