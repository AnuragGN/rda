@extends ('seeker.layouts.main', ['container' => 'container'])

@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Staff Management'])

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card card-info">
						<div class="card-header text-uppercase">Staff Management Info</div>
							<div class="card-body">

								<div id="js-response-success" class="alert alert-success" style="display: none;">
								  <span id="js-response-success-msg">Success message</span>
								  <button type="button" class="close" data-hide="alert" aria-label="Close">
								    <span aria-hidden="true">&times;</span>
								  </button>
								</div>

								<div id="js-response-error" class="alert alert-danger" style="display: none;">
								  <span id="js-response-error-msg">Success message</span>
								  <button type="button" class="close" data-hide="alert" aria-label="Close">
								    <span aria-hidden="true">&times;</span>
								  </button>
								</div>

								<div class="row">
									<div class="col-lg-9">

								<p class="text-lg">
									<small>Please use this page to manage and authorize the contacts who represent your organization on this website. To approve a contact click "activate". Click the add button to add a new contact and assign a username/password.  "Admin" contacts can edit the organization profile (including this page) and submit grant applications. "Staff" contacts can submit applications only. Designate one contact as the default, who will receive automatic emails and reminders from the system.</small>
								</p>
								<div class="overlay-wrapper">
									<div id="js-loading-section" class="overlay" style="display: none;">
										<i class="fas fa-3x fa-sync-alt fa-spin"></i>
									</div>
									<meta name="csrf-token" content="{{ csrf_token() }}" />
									<table class="table table-responsive">
										<thead>
										<tr>
											<th style="width: 120px">Role</th>
											<th>Name</th>
											<th>Default Contact?</th>
											<th>Receive Email</th>
											<th style="width: 80px">Status</th>
										</tr>
										</thead>
										<tbody>
											@forelse($orgContacts as $i => $orgContact)
											    @include("seeker.org.staff-list-item", ['orgContact' => $orgContact])
											@empty
											    @include("utils.data-not-found", [])
											@endforelse
										</tbody>
									</table>
								</div>

								<div class="form-group mt-4">
									<a href="{{route('gs-account-add-profile')}}" class="btn btn-info btn-sm">Add Contact</a>
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

<script type="text/javascript">
	$(document).ready(function() {
		$( '.js-access-level-type' ).on('change', function(e) {
			if( $(this).is(":checked") ){ // check if the radio is checked
			    var val = $(this).val(); // retrieve the value
			    var contactId = $(this).attr("data-contactId");
			    var organizationId = $(this).attr("data-organizationId");
			    setOrganizationAccessLevel(organizationId, contactId,val);
			}
		});

		window.setOrganizationAccessLevel = function (organizationId, contactId, accessLevelType){
			// console.log(organizationId, contactId,accessLevelType);
			var _this = this;
	        var loadingSection = $('#js-loading-section');
	        loadingSection.show();

	        var formData = {'organization_id' : organizationId, 'contact_id' : contactId, 'access_level' : accessLevelType};
	        var eMessage  = "Some error occurred while processing your request!";
	        var sMessage  = "Updated successfully !!";

	        $.ajax({
	        	type: 'POST',
	        	dataType: 'json',
	            url: '/gs/org/update-staff-access-level',
	            data: formData,
	            headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
	            success: function (data) {

	                loadingSection.hide();
	                console.log('Response success. Data: ', data);

	                if (data.status == 200) {
	                    console.log('SUCCESS!');
	                    _this.onSuccessResponse(data.message ? data.message : sMessage);
	                } else {
	                    console.log('Error other!');
	                    _this.onErrorResponse(data.message ? data.message : eMessage);
	                }
	            },
	            error: function (e) {
	                loadingSection.hide();
	                console.log('Response error',e.responseText);

	                _this.onErrorResponse(eMessage);
	                if (e.status === 422) {
	                    var errors = $.parseJSON(e.responseText);
	                    console.log("errors: ", errors);
	                    $.each(errors, function (key, val) {
	                        console.log("K: ", key, " V: ", val);
	                    });
	                }
	            }
	        });
	        return false;
		}

		$( '.js-default-contact' ).on('change', function(e) {
			if( $(this).is(":checked") ){ // check if the radio is checked
			    var contactId = $(this).val(); // retrieve the value
			    var organizationId = $(this).attr("data-organizationId");
			    setOrganizationDefaultContact(organizationId, contactId);
			}
		});

		window.setOrganizationDefaultContact = function (organizationId, contactId){
			// console.log(organizationId, contactId,accessLevelType);
			var _this = this;
	        var loadingSection = $('#js-loading-section');
	        loadingSection.show();

	        var formData = {'organization_id' : organizationId, 'contact_id' : contactId};
	        var eMessage  = "Some error occurred while processing your request!";
	        var sMessage  = "Updated successfully !!";

	        $.ajax({
	        	type: 'POST',
	        	dataType: 'json',
	            url: '/gs/org/update-org-default-contact',
	            data: formData,
	            headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
	            success: function (data) {

	                loadingSection.hide();
	                console.log('Response success. Data: ', data);

	                if (data.status == 200) {
	                    console.log('SUCCESS!');
	                    _this.onSuccessResponse(data.message ? data.message : sMessage);
	                } else {
	                    console.log('Error other!');
	                    _this.onErrorResponse(data.message ? data.message : eMessage);
	                }
	            },
	            error: function (e) {
	                loadingSection.hide();
	                console.log('Response error',e.responseText);

	                _this.onErrorResponse(eMessage);
	                if (e.status === 422) {
	                    var errors = $.parseJSON(e.responseText);
	                    console.log("errors: ", errors);
	                    $.each(errors, function (key, val) {
	                        console.log("K: ", key, " V: ", val);
	                    });
	                }
	            }
	        });
	        return false;
		}

		$( '.js-receive-email' ).on('change', function(e) {
			var contactId = $(this).attr("data-contactId");
			var receiveEmail = '<?=constant('App\Models\Contact::OPTION_NO')?>';
			if( $(this).is(":checked") ){ // check if the radio is checked
			    var receiveEmail = '<?=constant('App\Models\Contact::OPTION_YES')?>';
			}
			updateContactReceivedEmail(contactId, receiveEmail);
		});

		window.updateContactReceivedEmail = function (contactId, receiveEmail){

			var _this = this;
	        var loadingSection = $('#js-loading-section');
	        loadingSection.show();

	        var formData = {'contact_id' : contactId, 'receive_email' : receiveEmail};
	        var eMessage  = "Some error occurred while processing your request!";
	        var sMessage  = "Updated successfully !!";

	        $.ajax({
	        	type: 'POST',
	        	dataType: 'json',
	            url: '/gs/org/update-contact-receive-email',
	            data: formData,
	            headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
	            success: function (data) {

	                loadingSection.hide();
	                console.log('Response success. Data: ', data);

	                if (data.status == 200) {
	                    console.log('SUCCESS!');
	                    _this.onSuccessResponse(data.message ? data.message : sMessage);
	                } else {
	                    console.log('Error other!');
	                    _this.onErrorResponse(data.message ? data.message : eMessage);
	                }
	            },
	            error: function (e) {
	                loadingSection.hide();
	                console.log('Response error',e.responseText);

	                _this.onErrorResponse(eMessage);
	                if (e.status === 422) {
	                    var errors = $.parseJSON(e.responseText);
	                    console.log("errors: ", errors);
	                    $.each(errors, function (key, val) {
	                        console.log("K: ", key, " V: ", val);
	                    });
	                }
	            }
	        });
	        return false;
		}

		$( '.js-contact-status-update' ).on('click', function(e) {
		    var contactId = $(this).attr("data-contactId");
		    var organizationId = $(this).attr("data-organizationId");
		    var currentStatusText = $(this).html();
		    var changeStatusText = 'activate';
		    var changedTo = '<?=constant('App\Models\OrganizationContact::STATUS_DENIED')?>';
		    if (currentStatusText == 'activate') {
		    	changedTo = '<?=constant('App\Models\OrganizationContact::STATUS_APPROVED')?>';
		    	changeStatusText = 'deactivate';
		    }
		    console.log(contactId, organizationId, changedTo);
		    setOrganizationContactStatus(organizationId, contactId,changedTo, changeStatusText);
		});

		window.setOrganizationContactStatus = function (organizationId, contactId, changedTo, changeStatusText){
			var _this = this;
	        var loadingSection = $('#js-loading-section');
	        loadingSection.show();

	        var formData = {'organization_id' : organizationId, 'contact_id' : contactId, 'status' : changedTo};
	        var eMessage  = "Some error occurred while processing your request!";
	        var sMessage  = "Updated successfully !!";

	        $.ajax({
	        	type: 'POST',
	        	dataType: 'json',
	            url: '/gs/org/update-staff-status',
	            data: formData,
	            headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
	            success: function (data) {
	                loadingSection.hide();
	                console.log('Response success. Data: ', data);
	                if (data.status == 200) {
	                    console.log('SUCCESS!');
	                    _this.onSuccessResponse(data.message ? data.message : sMessage);
	                    $('#'+contactId+'_status_link').html(changeStatusText);
	                } else {
	                    console.log('Error other!');
	                    _this.onErrorResponse(data.message ? data.message : eMessage);
	                }
	            },
	            error: function (e) {
	                loadingSection.hide();
	                console.log('Response error',e.responseText);

	                _this.onErrorResponse(eMessage);
	                if (e.status === 422) {
	                    var errors = $.parseJSON(e.responseText);
	                    console.log("errors: ", errors);
	                    $.each(errors, function (key, val) {
	                        console.log("K: ", key, " V: ", val);
	                    });
	                }
	            }
	        });
	        return false;
		}

		onErrorResponse = function (mesg) {
	       	$('#js-response-error-msg').html(mesg);
	    	$('#js-response-error').show();
	    }

	    onSuccessResponse = function (mesg) {
	    	$('#js-response-success-msg').html(mesg);
	    	$('#js-response-success').show();
	    }

		$('[data-hide]').on('click', function(e) {
	        $(this).closest("." + $(this).attr("data-hide")).hide();
	    });

	});
</script>

@endsection




