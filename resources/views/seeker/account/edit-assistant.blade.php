@extends ('seeker.layouts.main')
@section ('content')

	@include('seeker.account.header', ['pageTitle' => 'Edit Assistant'])

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card card-info">
						<div class="card-header text-uppercase">Edit Assistant Info</div>
						<form class="form-horizontal">
							<div class="card-body">
										<!-- Assistant section -->
										<div class="form-group row">
											<label class="col-sm-3 col-form-label text-right">First Name</label>
											<div class="col-sm-6">
												<input type="text" name="a_first_name" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-3 col-form-label text-right">Last Name</label>
											<div class="col-sm-6">
												<input type="text" name="a_last_name" class="form-control">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-3 col-form-label text-right">Phone Number</label>
											<div class="col-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">+1</span>
													</div>
													<input type="text" class="form-control js_phone_format" name="a_phone" id="id_a_phone" required>
												</div>
											</div>
										</div>
							</div>

							<div class="card-footer">
								<div class="row">
									<div class="offset-sm-3 col-md-3">
										<input name="save" id="id_save_btn" class="btn btn-accent w100" type="submit" value="Change Password">
									</div>
								</div>

								{{--<button type="submit" class="btn btn-accent w100">Save</button>--}}
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
