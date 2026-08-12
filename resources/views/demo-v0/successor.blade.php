@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                {{--<p class="registration-form-info">Please fill the Individual Account Holder information below</p>--}}

                <p class="form-title th-color">Successor - Individual Account Holder Info</p>

                <p>Account Holders may name individuals (Individual Account Holders) to succeed them on their donor
                    advised fund account and/or may recommend one or more IRS-qualified public charities (charitable
                    organizations) to receive part or all of the balance of their account. If, at notification of death
                    of the last remaining Account Holder, there are no successors, HighGround Advisors will redeem the
                    remaining units in the account and distribute the proceeds according to the giving history over the
                    past 3 years. You may name up to 4 successors for each option. The total successor percentage must
                    add up to 100%.</p>

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
                        <label for="id-suffix"
                               class="col-sm-3 col-form-label form-multi-line-label text-right">Suffix<br>(optional)</label>

                        <div class="col-sm-3">
                            <input id="id-suffix" name="suffix" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-ssn" class="col-sm-3 col-form-label text-right">SSN#</label>

                        <div class="col-sm-3">
                            <input id="id-ssn" name="ssn" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-share-value" class="col-sm-3 col-form-label text-right">% of XXX Account</label>

                        <div class="col-sm-3">
                            <input id="id-share-value" name="share-value" type="text" class="form-control"
                                   placeholder="">
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
                            <input id="id-phone" name="phone" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-mobile" class="col-sm-3 col-form-label text-right form-multi-line-label">Evening
                            Phone<br>(optional)</label>

                        <div class="col-sm-3">
                            <input id="id-mobile" name="mobile" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-email" class="col-sm-3 col-form-label text-right">Email</label>

                        <div class="col-sm-6">
                            <input id="id-email" name="email" type="email" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-relation" class="col-sm-3 col-form-label form-multi-line-label text-right">Relationship
                            with Account Holder</label>

                        <div class="col-sm-3">
                            <input id="id-relation" name="relation" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group form-group-title">
                        <span>Legal/Residential Address</span>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-one" class="col-sm-3 col-form-label text-right">Address Line 1</label>

                        <div class="col-sm-6">
                            <input id="id-address-one" name="addressOne" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-two" class="col-sm-3 col-form-label text-right form-multi-line-label">Address
                            Line 2<br>(optional)</label>

                        <div class="col-sm-6">
                            <input id="id-address-two" name="addressTwo" type="text" class="form-control"
                                   placeholder="">
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
                            <input id="id-state" name="state" type="text" class="form-control" placeholder=""
                                   required="">
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
                            <input id="id-country" name="country" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group form-group-title">
                        <span>Charitable Organization</span>
                    </div>

                    <p>To make your contribution to the account, complete the applicable section below and check the
                        appropriate box. If your employer matches charitable contributions to charities with
                        donor-advised fund programs, please include the appropriate company paperwork. Any contribution,
                        once accepted by HighGround Advisors, represents an irrevocable contribution and is not
                        refundable.</p>

                    <div class="form-group row">
                        <label for="id-giving" class="col-sm-3 col-form-label text-right">% of Giving Account</label>

                        <div class="col-sm-6">
                            <input id="id-giving" name="giving" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-org-name" class="col-sm-3 col-form-label text-right">Organization Name</label>

                        <div class="col-sm-6">
                            <input id="id-org-name" name="org-name" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-ftpid" class="col-sm-3 col-form-label text-right">Federal Tax Payer ID#</label>

                        <div class="col-sm-6">
                            <input id="id-ftpid" name="ftpid" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

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
                    </div>

                    <div class="form-group row">
                        <label for="id-phone" class="col-sm-3 col-form-label text-right">Phone</label>

                        <div class="col-sm-3">
                            <input id="id-phone" name="phone" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group form-group-title">
                        <span>Charitable Organization Address</span>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-one" class="col-sm-3 col-form-label text-right">Address Line 1</label>

                        <div class="col-sm-6">
                            <input id="id-address-one" name="addressOne" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-address-two" class="col-sm-3 col-form-label text-right form-multi-line-label">Address
                            Line 2<br>(optional)</label>

                        <div class="col-sm-6">
                            <input id="id-address-two" name="addressTwo" type="text" class="form-control"
                                   placeholder="">
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
                            <input id="id-state" name="state" type="text" class="form-control" placeholder=""
                                   required="">
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
                            <input id="id-country" name="country" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="add-more">
                            <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add another charitable
                                organization</a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-10 col-form-label">
                            <span class="label-bold">Total % of Giving Account x%</span>
                            <br><span class="label-note">Total must equal 100%</span>
                        </label>
                    </div>

                    <div class="form-btn-bar">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <div id="id-next-btn">
                                    <a href="/donor/contribution" class="btn btn-hga-md btn-wide btn-theme">Save
                                        & Next</a>
                                </div>
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

    <script>
        $(function () {
            $("#id-dob").daterangepicker({
                singleDatePicker: true,
            });
        });
    </script>


@endsection
