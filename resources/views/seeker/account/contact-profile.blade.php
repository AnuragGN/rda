@extends ('seeker.layouts.main', ['container' => 'container'])

<style>
    .container-edit-profile {
        background: #eee;
    }
</style>


@section ('content')

    <div class="row clearfix">
        <div class="col-12">
            <div class="card">
			  	<h5 class="card-header text-uppercase">Your Organization Contact Info</h5>
			  	<div class="card-body">
			    	<div class="clearfix"></div>
			    	<form class="gs-form clearfix">
					    <div class="row">
						    <div class="col-8">
								<div class="form-group row">
								    <label class="col-sm-4 col-form-label text-right">Prefix</label>
								    <div class="col-sm-5">
								      	<select name="state" class="form-control">
								          <option value="Mr.">Mr.</option>
								          <option value="Ms.">Ms.</option>
								        </select>
								    </div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Title</label>
									<div class="col-sm-8">
										<input type="text" name="title" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">First Name</label>
									<div class="col-sm-8">
									  	<input type="text" name="first_name" class="form-control" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Middle Name</label>
									<div class="col-sm-8">
									  	<input type="text" name="middle_name" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Last Name</label>
									<div class="col-sm-8">
									  	<input type="text" name="last_name" class="form-control" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Suffix 1</label>
									<div class="col-sm-5">
									  	<select name="suffix1" class="form-control">
									      <option value=""></option>
									      <option value="AEP">AEP</option>
									    </select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Suffix 2</label>
									<div class="col-sm-5">
									  	<select name="suffix2" class="form-control">
									      <option value=""></option>
									      <option value="CPA">CPA</option>
									    </select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Email Address</label>
									<div class="col-sm-8">
									  <input type="email" name="preferred_email" class="form-control" required>
									</div>
								</div>
								<div class="form-group row mb-5">
									<label class="col-sm-4 col-form-label text-right">Direct Phone Number</label>
									<div class="col-8">
										<div class="input-group">
											<div class="input-group-prepend">
											    <span class="input-group-text">+1</span>
											</div>
											<input type="text" class="form-control js_phone_format" name="phone" id="id_phone" required>
										</div>
									</div>
								</div>

								<!-- Assistant section -->

								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Assistant First Name</label>
									<div class="col-sm-8">
									  	<input type="text" name="a_first_name" class="form-control">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Assistant Last Name</label>
									<div class="col-sm-8">
									  	<input type="text" name="a_last_name" class="form-control">
									</div>
								</div>
								<div class="form-group row mb-5">
									<label class="col-sm-4 col-form-label text-right">Assistant Phone Number</label>
									<div class="col-8">
										<div class="input-group">
											<div class="input-group-prepend">
											    <span class="input-group-text">+1</span>
											</div>
											<input type="text" class="form-control js_phone_format" name="a_phone" id="id_a_phone" required>
										</div>
									</div>
								</div>


								<div class="form-group row">
									<div class="col-sm-8 offset-sm-4">
									  <button type="submit" class="btn btn-primary">Save</button>
									</div>
								</div>
					        </div>
					        <div class="clearfix"></div>
					    </div>
			    	</form>
			  	</div>
			</div>
        </div>
    </div>

@endsection




