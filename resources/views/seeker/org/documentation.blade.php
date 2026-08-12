@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Documentation'])
	<section class="content">
		<div class="container">
		    <div class="row">
		        <div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Documentation</div>
					  	<div class="card-body">
					  		<div>
					  			<p class="text-lg">
					  				Please provide, or update the documentation that we have on file for your organization.
					  			</p>
					  		</div>
							<table class="table table-secondary table-responsive">
			                  <thead>
			                    <tr>
			                      <th style="width: 160px">Document</th>
			                      <th>Received</th>
			                      <th>Approved</th>
			                      <th>Expires</th>
			                      <th>Status</th>
			                      <th style="width: 300px">Update File(s)</th>
			                    </tr>
			                  </thead>
			                  <tbody>	                    
			                  </tbody>
			                </table>

			                <div class="form-group mt-4">
								<div class="col-12 col-md-8 offset-md-4">
									<b>Upload Your File</b>
								</div>
							</div>

							<div class="form-group row mt-4">
								<div class="col-12 col-md-4">
									<b>IRS Letter</b>
								</div>
								<div class="col-12 col-md-8">
									  <div class="form-group">
									    <input type="file" class="form-control-file" name="file_type_166">
									  </div>
									  <div class="form-group">
									    <h5>Or, update this file using the following alternate method</h5>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_166" value="browse" checked>
										  <label class="form-check-label">
										    I want to upload the file
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_166" value="fax">
										  <label class="form-check-label">
										    Fax 
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_166" value="mail">
										  <label class="form-check-label">
										    Mail
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_166" value="notrequired">
										  <label class="form-check-label">
										    Not required for our organization
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_166" value="url">
										  <label class="form-check-label">
										    Use the following URL
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="col-12 col-md-6">
									      <input type="text" class="form-control" name="url_166" value="">
									  </div>
								</div>
							</div>

							<div class="form-group row mt-4">
								<div class="col-12 col-md-4">
									<b>990</b>
								</div>
								<div class="col-12 col-md-8">
									  <div class="form-group">
									    <input type="file" class="form-control-file" name="file_type_167">
									  </div>
									  <div class="form-group">
									    <h5>Or, update this file using the following alternate method</h5>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_167" value="browse">
										  <label class="form-check-label">
										    I want to upload the file
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_167" value="fax" checked>
										  <label class="form-check-label">
										    Fax 
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_167" value="mail">
										  <label class="form-check-label">
										    Mail
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_167" value="notrequired">
										  <label class="form-check-label">
										    Not required for our organization
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="form-check">
									      <input class="form-check-input" type="radio" name="alt_method_167" value="url">
										  <label class="form-check-label">
										    Use the following URL
										    <a href="#">
										    	<i class="fa fa-info-circle" aria-hidden="true"></i>
											</a>
										  </label>
									  </div>
									  <div class="col-12 col-md-6">
									      <input type="text" class="form-control" name="url_167" value="">
									  </div>
								</div>
							</div>
					  	</div>
			  	        <div class="card-footer">
		                    <button type="submit" class="btn btn-accent">Save</button>
		                    <button type="submit" class="btn btn-accent ml-2">Save/Continue</button>
		                    <button type="submit" class="btn btn-default float-right">Cancel</button>
		                </div>
					</div>
		        </div>
		    </div>
		</div>
	</section>
@endsection




