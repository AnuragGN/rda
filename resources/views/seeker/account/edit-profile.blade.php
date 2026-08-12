@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Organization Contact'])
	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Edit Profile</div>
		            	@include('seeker.account._form_profile')
					</div>
		        </div>
		    </div>
		</div>
	</section>
@endsection




