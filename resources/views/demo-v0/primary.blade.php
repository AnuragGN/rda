@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form form-primary-account-holder">
        <div class="row ">
            <div class="col-lg-8">

                <p>Each of the Account Holders named on the donor advised fund account have full and equal privileges. There can be up to four Account Holders, with one individual serving as the Primary Account Holder. All correspondence will be sent to the Primary Account Holder, except confirmations related to contributions made by Additional Account Holders. If necessary, attach an additional page naming up to 2 additional Account Holders.</p>

                {{--<p class="registration-form-info">Complete the forms below to open a Giving Fund with HighGround Advisors.</p>--}}

                <p class="form-title th-color">PRIMARY ACCOUNT HOLDER INFO</p>


                <form id="registration-form">
                    <div class="form-group row">
                        <label for="id-prefix" class="col-sm-3 col-form-label text-right">Prefix</label>
                        <div class="col-sm-3">
                            <input id="id-prefix" name="prefix" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row account-name">
                        <label for="id-fname" class="col-sm-3 col-form-label text-right">Name</label>
                        <div class="col-sm-3">
                            <input id="id-fname" name="fname" type="text" class="form-control" placeholder="first name" required="">
                        </div>

                        <div class="middle-name">
                            <input id="id-mname" name="mname" type="text" class="form-control" placeholder="middle initial">
                        </div>

                        <div class="col-sm-3">
                            <input id="id-lname" name="lname" type="text" class="form-control" placeholder="last name" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-suffix" class="col-sm-3 col-form-label form-multi-line-label text-right">Suffix<br>(optional)</label>
                        <div class="col-sm-3">
                            <input id="id-suffix" name="suffix" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-prefname" class="col-sm-3 col-form-label text-right">Preferred Name</label>
                        <div class="col-sm-3">
                            <input id="id-prefname" name="prefname" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-ssn" class="col-sm-3 col-form-label text-right">SSN#</label>
                        <div class="col-sm-3">
                            <input id="id-ssn" name="ssn" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-dob" class="col-sm-3 col-form-label text-right">Date of Birth</label>
                        <div class="col-sm-3">
                            <input id="id-dob" name="dob" type="text" class="form-control" placeholder="mm/dd/yyyy">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-phone" class="col-sm-3 col-form-label text-right">Day Phone*</label>
                        <div class="col-sm-3">
                            <input id="id-phone" name="phone" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-mobile" class="col-sm-3 col-form-label text-right form-multi-line-label">Evening Phone<br>(optional)</label>
                        <div class="col-sm-3">
                            <input id="id-mobile" name="mobile" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-email" class="col-sm-3 col-form-label text-right">Email</label>
                        <div class="col-sm-6">
                            <input id="id-email" name="email" type="email" class="form-control" placeholder="" required="">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="id-email" class="col-sm-3 col-form-label text-right">Citizenship</label>
                        <div class="col-sm-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">U.S. Citizen</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">U.S. resident alien</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group form-group-title">
                        <span>Legal/Residential Address</span>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-one" class="col-sm-3 col-form-label text-right">Address Line 1</label>
                        <div class="col-sm-6">
                            <input id="id-address-one" name="addressOne" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-two" class="col-sm-3 col-form-label text-right form-multi-line-label">Address Line 2<br>(optional)</label>
                        <div class="col-sm-6">
                            <input id="id-address-two" name="addressTwo" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-city" class="col-sm-3 col-form-label text-right">City</label>
                        <div class="col-sm-3">
                            <input id="id-city" name="city" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-state" class="col-sm-3 col-form-label text-right">State</label>
                        <div class="col-sm-3">
                            <input id="id-state" name="state" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-ziip" class="col-sm-3 col-form-label text-right">ZIP</label>
                        <div class="col-sm-3">
                            <input id="id-ziip" name="ziip" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-country" class="col-sm-3 col-form-label text-right">Country</label>
                        <div class="col-sm-3">
                            <input id="id-country" name="country" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>


                    <div class="form-group form-group-title">
                        <span>Mailing Address</span>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="same-as">
                            <label class="form-check-label" for="same-as">Same as Legal/Residential address</label>
                        </div>
                    </div>

                    <div class="mailing-address">

                        <div class="form-group row">
                            <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Address Line 1</label>
                            <div class="col-sm-6">
                                <input id="id-mail-address-one" name="addressOne" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-address-two" class="col-sm-3 col-form-label text-right form-multi-line-label">Address Line 2<br>(optional)</label>
                            <div class="col-sm-6">
                                <input id="id-mail-address-two" name="addressTwo" type="text" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-city" class="col-sm-3 col-form-label text-right">City</label>
                            <div class="col-sm-3">
                                <input id="id-mail-city" name="city" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-state" class="col-sm-3 col-form-label text-right">State</label>
                            <div class="col-sm-3">
                                <input id="id-mail-state" name="state" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-ziip" class="col-sm-3 col-form-label text-right">ZIP</label>
                            <div class="col-sm-3">
                                <input id="id-mail-ziip" name="ziip" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                    </div>

                    <div class="form-btn-bar text-center">
                        <div id="id-next-btn" class="col-12 form-footer">
                            <a href="javascript:void(0);" class="form-info">+ ADD ANOTHER ACCOUNT HOLDER<br></a>
                            <span class="form-note">(add up to 3 additional account holders who each have full and equal privileges)<br><br></span>
                            <a href="/donor/account-name" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-lg-4">
                @include('demo.side-pane')
            </div>

        </div>

    </div>

    <script>
        $(function () {
            $("#id-dob").daterangepicker({
                singleDatePicker: true,
            });
        });
    </script>

@endsection
