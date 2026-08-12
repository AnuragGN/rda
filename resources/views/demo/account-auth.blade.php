@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    <style>
        .box {
            background: #fcfcfc;
            padding: 0 1rem;
            border: 1px solid #f5f5f5;
            border-radius: 4px;
        }
    </style>
    @include('demo.tabs')

    <div class="container registration-form">
        <form id="registration-form">

            <div class="row ">
                <div class="col-lg-6">

                    <div class="box">
                        <p class="form-title th-color">Giving Account Name</p>

                        <div class="form-group row">
                            <label for="id-name" class="col-sm-3 col-form-label text-right">Giving Account Name</label>
                            <div class="col-sm-6">
                                <input id="id-name" name="fname" type="text" class="form-control" placeholder='e.g. "The Smith Family Fund"' required="">
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="box">
                        <p class="form-title th-color">Signature</p>

                        <div class="form-group row">
                            <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Account
                                Holder</label>
                            <label for="id-mail-address-one" class="col-sm-9 col-form-label label-bold">John Smith</label>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Signature</label>

                            <div class="col-sm-9">
                                <div class="btn-group btn-signature w-100" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-switch btn-docusign active" onclick="onDocusign()">
                                        Docusign
                                    </button>
                                    <button type="button" class="btn btn-switch btn-signhere" onclick="onSignHere()">Sign
                                        here
                                    </button>
                                </div>
                                <div class="sig-box">
                                    <div class="sig-box-content docusign">
                                        <a href="/">Click to get from Docusign</a>
                                    </div>
                                    <div class="sig-box-content signhere hide">
                                        <span>Sign here</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Secondary --}}

                        <div class="form-group row">
                            <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Additional Account
                                Holder</label>
                            <label for="id-mail-address-one" class="col-sm-9 col-form-label label-bold">Johnson
                                Smith</label>
                        </div>

                        <div class="form-group row">
                            <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Signature</label>

                            <div class="col-sm-9">
                                <div class="btn-group btn-signature w-100" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-switch btn-docusign active" onclick="onDocusign()">
                                        Docusign
                                    </button>
                                    <button type="button" class="btn btn-switch btn-signhere" onclick="onSignHere()">Sign
                                        here
                                    </button>
                                </div>
                                <div class="sig-box">
                                    <div class="sig-box-content docusign">
                                        <a href="/">Click to get from Docusign</a>
                                    </div>
                                    <div class="sig-box-content signhere hide">
                                        <span>Sign here</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-date" class="col-sm-3 col-form-label text-right">DATE</label>

                            <div class="col-sm-3">
                                <input id="id-dob" name="dob" type="text" class="form-control" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                    </div>


                </div>

                <div class="col-lg-6">

                    <div class="box">
                        <p class="form-title th-color">Investment Pool Selection</p>

                        <p class="">Please select maximum of two entries</p>

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label label-bold">Fund Name</label>
                            <label class="col-sm-5 col-form-label text-right label-bold">% of total</label>
                        </div>

                        {{-- 1 --}}
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-5">
                                    <label class="form-check-label auth-label" for="id-pool-5">HighGround Enhanced Cash Fund
                                        <br><small>(20% Growth, 50% Fixed, 30% Cash)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="pool-value">
                                    <input id="id-pool-4-value" name="pool-4-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 2 --}}
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-3">
                                    <label class="form-check-label auth-label" for="id-pool-3">HighGround Conservative Fund
                                        <br><small>(20% Growth, 50% Fixed, 30% Cash)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="pool-value">
                                    <input id="id-pool-3-value" name="pool-3-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 3 --}}
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-2">
                                    <label class="form-check-label auth-label" for="id-pool-2">HighGround Balanced Fund
                                        <br><small>(20% Growth, 50% Fixed, 30% Cash)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="pool-value">
                                    <input id="id-pool-2-value" name="pool-2-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4 --}}
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-1">
                                    <label class="form-check-label auth-label" for="id-pool-1">HighGround Growth Fund
                                        <br><small>(20% Growth, 50% Fixed, 30% Cash)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="pool-value">
                                    <input id="id-pool-1-value" name="pool-1-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-4">
                                    <label class="form-check-label auth-label" for="id-pool-4">HighGround Keystone Fund
                                        <br><small>(20% Growth, 50% Fixed, 30% Cash)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="pool-value">
                                    <input id="id-pool-4-value" name="pool-4-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-11 col-form-label text-right">
                                <span class="label-bold">Total x%</span>
                                <br><span class="label-note">Total must equal 100%</span>
                            </label>
                        </div>

                    </div>

                </div>

                <div class="row ">
                    <div class="col-lg-12">
                        <br>
                    </div>

                    <div class="col-lg-9">

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <span>You, as the Primary Account Holder may authorize an individual other than an Account Holder to receive duplicate statements, obtain information and or/perform transactions on your behalf. To facilitate this activity, please complete a donor advised fund account access form. This form can be found online at HighGroundAdvisorsDAF.org or by calling 1.800.xxx.xxxx.</span>
                            </div>
                        </div>

                        {{--<p class="registration-form-info">Please check below to consent.</p>--}}

                        <p class="form-title th-color">Authorization</p>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <textarea id="id-name" name="fname" rows="5" type="text" class="form-control" disabled="disabled" placeholder='Lorem ipsum'></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline form-check-authorize">
                                <input class="form-check-input" type="checkbox" id="same-as">
                                <label class="form-check-label" for="same-as">I Authorize</label>
                            </div>
                            <p id="auth-info-error" class="field-error">Please select checkbox.</p>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10">
                                <div id="id-next-btn" class="col-9 form-footer">
                                    <a href="/donor/successor" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </form>
    </div>


@endsection
