@extends ('seeker.layouts.main')

@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Organization'])

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card card-info">
						<div class="card-header text-uppercase">Organization Info</div>
						{{@include('seeker.org._form_org_profile')}}

						{!! Form::model($org, ['method' => "POST", 'files' => false, 'id' => 'form-organization-profile', 'class' => 'form-horizontal' ]) !!}
						@include('errors.form-errors')
						<div class="card-body">

							<div class="form-group row">
								<div class="col-12">
									<h5 class="card-title">Provide your organization's primary contact information below.</h5>
								</div>
							</div>

						</div>


					</div>
				</div>
			</div>
		</div>
	</section>

@endsection
