@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Certifications'])
	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Certifications</div>
		            	{!! Form::open( ['method' => "POST", 'files' => false, 'id' => 'form-certifications', 'class' => 'gs-form clearfix' ]) !!}
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h4>Please accept the policy of the JCF</h4>
						  	</div>
					  		<div class="mb-4">
						  		<h5>Non-Discrimination Policy</h5>
						  	</div>
					  		<div class="mb-4">
					  			<p class="text-lg">
					  				The Foundation for Inspired Giving requires all grantees to comply with its non-discrimination policy. By clicking .I Agree. below, you are confirming that your organization does not discriminate against employees, volunteers, board members, or the members, clients or students its serves on the basis of race, color, religion, gender, national origin, age, medical condition, veteran status, marital status, disability, ancestry, sexual orientation or any other characteristic protected by law.
					  			</p>
					  		</div>
					  		<div class="mb-4">
					  			<p class="text-lg">
					  				<strong>
					  					You are confirming that this organization does not discriminate.
					  				</strong>
					  			</p>
					  			<p class="text-lg">
					  				If you have any questions, comments, or concerns please use the text box below.
					  			</p>
					  		</div>

					  		<div class="form-group mb-4">
							    <textarea class="form-control" id="user_comments" name="user_comments" rows="3"></textarea>
							</div>
					  	</div>
					  	<div class="card-footer">
					  		{{ Form::button('<i class="far fa-handshake"></i> <strong>I Agree</strong>', ['type' => 'submit', 'id' =>'id_save_btn', 'class' => 'btn btn-accent', 'name' => 'agree'] )  }}

					  		{{ Form::button('<i class="far fa-thumbs-down"></i> <strong>I Do Not Agree</strong>', ['type' => 'submit', 'id' =>'id_save_btn', 'class' => 'btn btn-accent ml-2', 'name' => 'dont-agree'] )  }}

                            <button type="submit" class="btn btn-default float-right">Cancel</button>
                        </div>
                        {!! Form::close() !!}
					</div>
		        </div>
		    </div>
		</div>
	</section>

@endsection




