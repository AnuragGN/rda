@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                <p class="registration-form-info">Please select the Investment Pools below.</p>

                <p class="form-title th-color">Investment Pool Selection</p>

                <p class="">Please select maximum of two entries</p>

                <form id="registration-form">

                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label label-bold">Fund Name</label>
                        <label class="col-sm-5 col-form-label text-right label-bold">% of total</label>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="id-pool-1">
                                <label class="form-check-label auth-label" for="id-pool-1">HighGround Growth Fund</label>
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
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="id-pool-2">
                            <label class="form-check-label auth-label" for="id-pool-2">HighGround Balanced Fund</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="pool-value">
                                <input id="id-pool-2-value" name="pool-2-value" type="text" class="form-control" placeholder="" required="">
                                <span>%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="id-pool-3">
                            <label class="form-check-label auth-label" for="id-pool-3">HighGround Conservative Fund</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="pool-value">
                                <input id="id-pool-3-value" name="pool-3-value" type="text" class="form-control" placeholder="" required="">
                                <span>%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="id-pool-4">
                            <label class="form-check-label auth-label" for="id-pool-4">HighGround Capstone Fund</label>
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
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="id-pool-5">
                            <label class="form-check-label auth-label" for="id-pool-5">HighGround Enhanced Cash Fund</label>
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
                        <label class="col-sm-10 col-form-label text-right">
                            <span class="label-bold">Total x%</span>
                            <br><span class="label-note">Total must equal 100%</span>
                        </label>
                    </div>


                    <div class="form-btn-bar">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <div id="id-next-btn">
                                    <a href="/donor/signature" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
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


@endsection
