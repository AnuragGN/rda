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
			  	<h5 class="card-header text-uppercase">Edit Profile</h5>
			  	<div class="card-body">
			    	<div class="clearfix"></div>
			    	<form class="gs-form clearfix">
					    <div class="row">
						    <div class="col-12 col-lg-6">
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
									<label class="col-sm-4 col-form-label text-right">Suffix</label>
									<div class="col-sm-5">
									  	<select name="suffix" class="form-control">
									      <option value=""></option>
									      <option value="AEP">AEP</option>
									    </select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Email Address</label>
									<div class="col-sm-8">
									  <input type="email" name="preferred_email" class="form-control" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Home Phone Number</label>
									<div class="col-8">
										<div class="input-group">
											<div class="input-group-prepend">
											    <span class="input-group-text">+1</span>
											</div>
											<input type="text" class="form-control js_phone_format" name="phone" id="id_phone">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right">Business Phone Number</label>
									<div class="col-8">
										<div class="input-group">
											<div class="input-group-prepend">
											    <span class="input-group-text">+1</span>
											</div>
											<input type="text" class="form-control js_phone_format" name="business_phone" id="id_business_phone">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label text-right pr-0">Cell Phone Number</label>
									<div class="col-8">
										<div class="input-group">
											<div class="input-group-prepend">
											    <span class="input-group-text">+1</span>
											</div>
											<input type="text" class="form-control js_phone_format" name="cellular_phone" id="id_cellular_phone">
										</div>
									</div>
								</div>

								

								<div class="form-group row">
									<div class="col-sm-8 offset-sm-4">
									  <button type="submit" class="btn btn-primary">Save</button>
									</div>
								</div>
					        </div>
					        <div class="col-12 col-lg-6">
					        	<div class="card card-secondary">
						        	<div class="card-header">
						                <h3 class="card-title">ADDRESS</h3>
						            </div>
						            <div class="card-body">
										<div class="form-group row">
										    <label class="col-sm-3 col-form-label text-right pr-0">Address Line 1</label>
										    <div class="col-sm-9">
										      <input type="text" name="address1" class="form-control" size="28" required>
										    </div>
										</div>
										<div class="form-group row">
										    <label class="col-sm-3 col-form-label text-right pr-0">Address Line 2</label>
										    <div class="col-sm-9">
										      <input type="text" name="address2" class="form-control" size="28">
										    </div>
										</div>
										<div class="form-group row">
										    <label class="col-sm-3 col-form-label text-right pr-0">City</label>
										    <div class="col-12 col-lg-7">
										      <input type="text" name="city" class="form-control" required>
										    </div>
										</div>
										<div class="form-group row">
										    <label class="col-sm-3 col-form-label text-right pr-0">State</label>
										    <div class="col-12 col-lg-7">
										      	<select name="state" class="form-control">
										          <option value="Alabama">Alabama</option>
										          <option value="Alaska">Alaska</option>
										          <option value="Arizona">Arizona</option>
										        </select>
										    </div>
										  </div>
										<div class="form-group row">
										    <label class="col-sm-3 col-form-label text-right pr-0">Zip Code</label>
										    <div class="col-12 col-lg-7">
										      <input type="text" name="zip" class="form-control" maxlength="10" id="id_zip" required>
										    </div>
										</div>	
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




