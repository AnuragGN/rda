@extends ('seeker.layouts.main')

@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Organization'])

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card card-info">
						<div class="card-header text-uppercase">Organization Info</div>
						@include('seeker.org._form_org_profile')
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection
