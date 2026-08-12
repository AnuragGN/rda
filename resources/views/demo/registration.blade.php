<?php
$loggedInRequired = true;
?>

@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            <div class="row">
                <div class="col-8">
                    <form id="id-form-register">
                        <div class="form-group">
                            <p class="form-title th-color">Create Donor-Advised Fund Login</p>
                            <p>
                                Please fill in the form below to create you DAF account.
                                After account creation, you can login to complete the Donor-Advised Fund application.
                            </p>
                        </div>


                        <div class="form-group row account-name">
                            <label for="id-fname" class="col-md-3 col-form-label text-right">Name</label>
                            <div class="col-md-6 pl-0">
                                <div class="field-name">
                                    <div class="first-name">
                                        <input id="id-fname" name="fname" type="text" class="form-control" placeholder="first name" required="">
                                    </div>
                                    <div class="last-name">
                                        <input id="id-lname" name="lname" type="text" class="form-control" placeholder="last name" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="offset-md-3">
                                <p id="error-name" style="display: none" class="field-error">Error message</p>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="id-email" class="col-md-3 col-form-label text-right">Email</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-email" name="email" type="email" class="form-control" placeholder="" required="">
                                <p id="error-email" style="display: none" class="field-error">Error message</p>
                            </div>
                        </div>

                        <div class="form-group row field-password">
                            <label for="id-password" class="col-md-3 col-form-label text-right">Password</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-password" name="password" type="password" class="form-control" placeholder="" required="">
                                <p id="error-password" style="display: none" class="field-error">Error message</p>
                                <div class="password-help" style="display: none">Password must be at least 8 character long and must have one uppercase character, one lowercase character, one special character and one number.</div>
                            </div>
                            <div class="col-md-1 info-help">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </div>

                        <div class="form-group row field-password">
                            <label for="id-confirm-password" class="col-md-3 col-form-label text-right">Confirm Password</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-confirm-password" name="password" type="password" class="form-control" placeholder="" required="">
                                <p id="error-confirm-password" style="display: none" class="field-error">Error message</p>
                            </div>
                        </div>


                        <div class="form-group row">

                            <div class="col-md-3"></div>

                            <div class="col-md-6 pl-0">
                                <div id="id-next-btn" class="col-6 form-footer">
                                    <a href="/registration/primary" class="btn btn-hga-md btn-wide btn-theme">SUBMIT</a>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>

                <div class="col-md-4">
                    @include('demo.side-pane-registration')
                </div>

            </div>


        </div>
    </div>

@endsection
