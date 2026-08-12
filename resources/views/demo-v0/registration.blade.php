<?php
$loggedInRequired = true;
?>

@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    {{--hero--}}
    <div class="container-fluid">
        <div class="row row-hero">
            <div class="hero-box">
                <img src="/images/demo/hero.jpg" alt="">
                <div class="caption">
                    <div class="container">
                        <span class="cap-title-big">OPEN YOUR FUND</span><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row ">
            <div class="col-lg-8">

                <p class="form-title th-color">CREATE NEW ACCOUNT</p>

                <form id="registration-form">

                    <div class="form-group row account-name">
                        <label for="id-fname" class="col-sm-3 col-form-label text-right">Name</label>
                        <div class="col-sm-6">
                            <div class="field-name">
                                <div class="first-name">
                                    <input id="id-fname" name="fname" type="text" class="form-control" placeholder="first name" required="">
                                </div>
                                <div class="middle-name">
                                    <input id="id-fname" name="fname" type="text" class="form-control" placeholder="mi" required="">
                                </div>
                                <div class="last-name">
                                    <input id="id-lname" name="lname" type="text" class="form-control" placeholder="last name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="offset-sm-3">
                            <p id="error-name" style="display: none" class="field-error">Error message</p>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="id-email" class="col-sm-3 col-form-label text-right">Email</label>
                        <div class="col-sm-6">
                            <input id="id-email" name="email" type="email" class="form-control" placeholder="" required="">
                            <p id="error-email" style="display: none" class="field-error">Error message</p>
                        </div>
                    </div>

                    <div class="form-group row field-password">
                        <label for="id-password" class="col-sm-3 col-form-label text-right">Password</label>
                        <div class="col-sm-6">
                            <input id="id-password" name="password" type="password" class="form-control" placeholder="" required="">
                            <p id="error-password" style="display: none" class="field-error">Error message</p>
                            <div class="password-help" style="display: none">Password must be at least 8 character long and must have one uppercase character, one lowercase character, one special character and one number.</div>
                        </div>
                        <div class="col-sm-1 info-help">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>

                    <div class="form-group row field-password">
                        <label for="id-confirm-password" class="col-sm-3 col-form-label text-right">Confirm Password</label>
                        <div class="col-sm-6">
                            <input id="id-confirm-password" name="password" type="password" class="form-control" placeholder="" required="">
                            <p id="error-confirm-password" style="display: none" class="field-error">Error message</p>
                        </div>
                    </div>


                    <div class="form-group row">

                        <div class="col-sm-3"></div>

                        <div class="col-sm-6">
                            <div id="id-next-btn" class="col-9 form-footer">
                                <a href="/donor/primary" class="btn btn-hga-md btn-wide btn-theme">Create Account</a>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

            <div class="col-lg-4">
                @include('demo.side-pane')
            </div>

        </div>

    </div>

@endsection
