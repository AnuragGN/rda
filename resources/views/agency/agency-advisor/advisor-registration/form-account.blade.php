@extends ('agency.agency-advisor.advisor-registration.main-account')

@section ('content')

@include('agency.agency-advisor.advisor-registration.form-header')

<div class="container custom-form pageTop">
    <div class="form-account-body">
        <div class="row">
            <div class="col-md-8">

				@if(!empty(session('sso_id')) || !empty($sso_id ?? null))

					<form method="POST" action="{{ route('post-advisor-account') }}" id="advisor-account-form">
						@csrf

						<!-- Hidden Fields -->
						<input type="hidden" name="sso_id"
							value="{{ old('sso_id', session('sso_id', $sso_id ?? '')) }}">

						<input type="hidden" name="firm_name"
							value="{{ old('firm_name', session('firm_name', $firm_name ?? '')) }}">

						<input type="hidden" name="partner_id"
							value="{{ old('partner_id', session('partner_id', $partner_id ?? '')) }}">

						<div class="form-group">
							<p class="form-title th-color">Complete advisor registration</p>
							<p class="mb-1">
								Please complete the form below to request access to an advisor account.
								An administrator will review your submission and activate the account if approved.
							</p>
							<p class="small text-muted">
								Fields marked with <span class="text-danger">*</span> are required.
							</p>
						</div>

						<div class="form-group">
							@include('errors.form-errors')
						</div>

						<!-- Firm Name -->
						<div class="form-group row align-items-center">
							<label class="col-md-3 col-form-label text-md-right pr-0">
								Advisor Firm Name
							</label>
							<div class="col-sm-8">
								<p class="form-control-plaintext mb-0">
									{{ old('firm_name', session('firm_name', $firm_name ?? 'Not available')) }}
								</p>
							</div>
						</div>

						<!-- Name -->
						<div class="form-group row align-items-center">
							<label class="col-md-3 col-form-label text-md-right pr-0">
								Name <span class="text-danger">*</span>
							</label>

							<div class="col-md-4 mb-1">
								<input type="text"
									   name="first_name"
									   class="form-control"
									   placeholder="First name"
									   value="{{ old('first_name', session('first_name')) }}"
									   required
									   onkeypress="return /[a-z]/i.test(event.key)">
							</div>

							<div class="col-md-4 mb-1">
								<input type="text"
									   name="last_name"
									   class="form-control"
									   placeholder="Last name"
									   value="{{ old('last_name', session('last_name')) }}"
									   required
									   onkeypress="return /[a-z]/i.test(event.key)">
							</div>
						</div>

						<!-- Email -->
						<div class="form-group row align-items-center">
							<label class="col-md-3 col-form-label text-md-right pr-0">
								Email <span class="text-danger">*</span>
							</label>

							<div class="col-md-8">
								<input type="email"
									   name="email"
									   class="form-control"
									   placeholder="Email address"
									   value="{{ old('email', session('email')) }}"
									   required>
							</div>
						</div>

						<!-- Phone -->
						<div class="form-group row align-items-center">
							<label class="col-md-3 col-form-label text-md-right pr-0">
								Phone
							</label>

							<div class="col-md-8">
								<input type="text"
									   name="phone"
									   class="form-control"
									   placeholder="Phone"
									   value="{{ old('phone') }}"
									   onkeypress="return /[0-9]/i.test(event.key)">
							</div>
						</div>

						<!-- Comment -->
						<div class="form-group row align-items-start">
							<label class="col-md-3 col-form-label text-md-right pr-0">
								Additional Information
							</label>

							<div class="col-md-8">
								<textarea name="comment"
										  class="form-control"
										  rows="2"
										  placeholder="Comment">{{ old('comment') }}</textarea>
							</div>
						</div>

						<!-- Checkbox -->
						<div class="form-group row">
							<div class="offset-md-3 col-md-9 xs-mt-2">
								<div class="form-check form-check-inline">
									<input class="checkbox-1x ml-1"
										   type="checkbox"
										   name="accept_advisor"
										   id="id_accept_advisor"
										   value="1"
										   @checked(old('accept_advisor'))>
									<label for="id_accept_advisor" class="col-form-label ml-2">
										I confirm I am a Registered Investment Advisor (RIA)
									</label>
								</div>
							</div>
						</div>

						<!-- Submit Button -->
						<div class="form-btn-bar">
							<div class="col-md-12 form-footer">
								<div class="row">
									<p class="offset-md-3 col-md-3">
										<button type="submit"
												name="save"
												id="id_save_btn"
												class="btn btn-wide btn-accent w100">
											Submit
										</button>
									</p>
								</div>
							</div>
						</div>

					</form>

                @else

					<div class="form-group mt-4 mb-4">
						<p class="form-title th-color mb-2">Advisor Registration Cannot Proceed</p>

						<p class="mb-2">
							A valid <strong>SSO ID</strong> was not detected for this session.
						</p>

						<p class="mb-2">
							Advisor registration must be initiated through your organization’s Single Sign-On (SSO) portal to ensure secure and verified access.
						</p>

						<p class="mb-0">
							<strong>Direct registration is not supported.</strong>
							Please return to your SSO portal and start the advisor registration process again.
						</p>
					</div>

                @endif

            </div>
        </div>
    </div>
</div>

@endsection
