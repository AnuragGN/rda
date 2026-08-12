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
                            <p class="form-title th-color">Donor Login</p>
                        </div>

                        <div class="form-group row mt-4">
                            <label for="id-email" class="col-md-3 col-form-label text-right">Email / Username</label>
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
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 pl-0">
                                <div id="id-next-btn" class="col-6 form-footer">
                                    <a href="/registration/primary" class="btn btn-hga-md btn-wide btn-theme">SUBMIT</a>
                                </div>
                                <a href="#" class="login-form-footer-text th-color">Forgot password?</a>
                            </div>
                        </div>

                    </form>

                    <br>
                    <hr class="mb-2">

                    <p class="login-form-footer-text">Don't have a Donor-Advised Fund account ? <a href="/registration/registration" class="th-color">CLICK HERE</a> to open a fund.</p>
                </div>

                <div class="col-md-4">
                    @include('demo.side-pane-registration')
                </div>

            </div>


        </div>
    </div>

@endsection
