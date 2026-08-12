@extends ('seeker.layouts.main', ['container' => 'container'])

<style>
    .container-edit-profile {
        background: #eee;
    }
</style>


@section ('content')

    <div class="row clearfix">
        <div class="col-12">
            <div class="card">
			  	<h5 class="card-header text-uppercase">Account Info</h5>
			  	<div class="card-body">
			    	<div class="clearfix"></div>
			    	<form class="gs-form clearfix">
					    <div class="row">
						    <div class="col-8">
							  	<div class="form-group row">
							    	<label class="col-sm-4 col-form-label text-right">Email / Username</label>
								    <div class="col-sm-8">
								    	<label class="col-form-label">lwelter</label>
								    </div>
							  	</div>
							  	<div class="mb-4">
							  		<hr/>
							  	</div>
								<div class="form-group row">
								    <label class="col-sm-4 col-form-label text-right">Current Password</label>
								    <div class="col-sm-8">
								    	<div class="input-group">
								    		<input class="form-control" required="required" name="current_password" type="password" id="current_password">
						                  	<div class="input-group-append">
								                <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer; color: #666;">
								                    <i class="fas fa-eye-slash" data-target-id="current_password" style="width: 20px;" onclick="showPassword(this)"></i>
								                    <i class="fas fa-eye" data-target-id="current_password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
								                </div>
								            </div>
						                </div>
								    </div>
								</div>
								<div class="form-group row">
								    <label class="col-sm-4 col-form-label text-right">New Password</label>
								    <div class="col-sm-8">
									    <div class="input-group">
									    	<input class="form-control" required="required" name="password" type="password" id="password">
									        <div class="input-group-append">
									            <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
									                <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
									                <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
									            </div>
									        </div>
									    </div>
									    <small id="passwordHelp" class="form-text">
									    	Passwords must be 6-20 characters in length and must include at least one uppercase letter, one lowercase letter, and one numeric digit.
									    </small>
								    </div>
								</div>
								<div class="form-group row">
								    <label class="col-sm-4 col-form-label text-right">Confirm New Password</label>
								    <div class="col-sm-8">
									    <div class="input-group">
									        <input class="form-control" required="required" name="password_confirmation" type="password" id="password_confirmation">
									        <div class="input-group-append">
									            <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
									                <i class="fas fa-eye-slash" data-target-id="password_confirmation" style="width: 20px;" onclick="showPassword(this)"></i>
									                <i class="fas fa-eye" data-target-id="password_confirmation" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
									            </div>
									        </div>
									    </div>
								    </div>
								</div>
								<div class="form-group row">
								    <div class="col-sm-8 offset-sm-4">
								      <button type="submit" class="btn btn-primary">Update</button>
								    </div>
								</div>
					        </div>
					        <div class="clearfix"></div>
					    </div>
			    	</form>
			  	</div>
			</div>
        </div>
    </div>

    <script>
	    function showPassword(item) {
	        var targetId = $(item).data('target-id');
	        var elem = $('#' + targetId);
	        elem.parent().find(".fa-eye").show();
	        elem.attr('type', 'text');
	        $(item).hide();
	    }
	    function hidePassword(item) {
	        var targetId = $(item).data('target-id');
	        var elem = $('#' + targetId);
	        elem.parent().find(".fa-eye-slash").show();
	        elem.attr('type', 'password');
	        $(item).hide();
	    }
	</script>

@endsection




