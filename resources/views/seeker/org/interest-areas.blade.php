@extends ('seeker.layouts.main')

@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Interest Areas'])

	<section class="content">
		<div class="container">
			<div class="form-wrapper form-last">
				<div class="row">
					<div class="col-xl-9">


						<p class="fw300">Please select which areas best classify the areas in which your organization serves.</p>
						<p class="fw300">Organization "Interest Areas" will provide an overview of your organization and its intentions. Later, when you add your programs and funding areas, you will have the opportunity to be more specific.</p>

						<div class="row">
							<div class="col-12">

								{!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['gs-org-interest-areas-save'], 'id' => 'gs-org-interest-areas']) !!}
								@include('errors.form-errors')
								@include('seeker.org._form_interests')
								{!! Form::close() !!}

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

@endsection




