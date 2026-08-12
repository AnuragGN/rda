@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Budget'])

	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Budget</div>
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h5>Your organization's finances may be an interesting item for donors to consider when reviewing your funding and program needs.</h5>
						  		<h5>You will have the opportunity to upload your financial information in the <b>Documents</b> page. If you would like to do that now, please click on <b>Documents</b> at the bottom of the left navigation panel.</h5>
						  		<h5>This is an optional piece of information and may be uploaded or updated at any time.</h5>
						  	</div>
						  	<div class="mb-4">
						  		<div class="form-group form-check">
								    <input type="checkbox" class="form-check-input" id="upload_document">
								    <label class="form-check-label" for="upload_document">Budget Uploaded to Documents</label>
								</div>
							</div>
							<div class="mb-4">

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

    <script type="text/javascript">
    	$(document).ready(function() {

		});
    </script>

@endsection



