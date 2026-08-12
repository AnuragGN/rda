@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                <p class="registration-form-info">Please fill the contribution details below.</p>

                <p class="form-title th-color">Contribution</p>

                <form id="registration-form">
                    <div class="form-group row">
                        <label for="id-fund-name" class="col-sm-3 col-form-label form-multi-line-label text-right">Security/Mutual Fund Name</label>
                        <div class="col-sm-6">
                            <input id="id-fund-name" name="fund-name" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-symbol" class="col-sm-3 col-form-label text-right">Name</label>
                        <div class="col-sm-6">
                            <input id="id-symbol" name="symbol" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-custodian" class="col-sm-3 col-form-label form-multi-line-label text-right">Custodian Account Number</label>
                        <div class="col-sm-6">
                            <input id="id-custodian" name="custodian" type="text" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-no-shares" class="col-sm-3 col-form-label text-right">Number of Shares</label>
                        <div class="col-sm-6">
                            <input id="id-no-shares" name="no-shares" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-dollars" class="col-sm-3 col-form-label text-right">Approx. Dollar Amount</label>
                        <div class="col-sm-6">
                            <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="add-more">
                            <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add more</a>
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<div class="form-check form-check-inline">--}}
                    {{--<input class="form-check-input" type="checkbox" id="same-as">--}}
                    {{--<label class="form-check-label" for="same-as">Click to add additional information</label>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group form-group-title">
                        <span>Cash Equivalents</span>
                    </div>

                    <p>HighGround Charitable® will not accept contributions of currency or certain cash-like monetary instruments, including cashier’s checks, treasurer’s checks, bank checks, official checks, bank drafts, traveler’s checks, postal money orders, or money orders.</p>

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="same-as">
                                <label class="form-check-label" for="same-as">Check</label>
                            </div>
                        </div>
                        <label for="id-dollars" class="col-sm-3 col-form-label text-right">Check Amount</label>
                        <div class="col-sm-3">
                            <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <p>Make checks payable to HighGround Charitable Gift Fund and reference the Giving Account number or name in the memo section.</p>

                    <p>Make your check to the following address: 123, Demo address.</p>

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="same-as">
                                <label class="form-check-label" for="same-as">Wire</label>
                            </div>
                        </div>
                        <label for="id-dollars" class="col-sm-3 col-form-label text-right">Wire Amount</label>
                        <div class="col-sm-3">
                            <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <label for="id-dollars" class="col-sm-3 col-form-label text-right">Bank Name</label>
                        <div class="col-sm-3">
                            <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <p>Send your wire amount information to the BANK OF AMERICA, Routing Number - xxx and Account Number - xxx</p>

                    <div class="form-group form-group-title">
                        <span>Securities or Mutual Funds Held at a Firm Other than HighGround</span>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="same-as">
                            <label class="form-check-label" for="same-as">Contribute securities or mutual funds held at a firm other than HighGround</label>
                        </div>
                    </div>

                    <p>A completed Letter of Instruction is required. Please refer to the attached Letter of Instruction Form for mailing instructions.</p>

                    <div class="form-group form-group-title">
                        <span>Stock Certificates Held in Personal Possession</span>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="same-as">
                            <label class="form-check-label" for="same-as">Contribute the following stock certificates</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-fname" class="col-sm-3 col-form-label text-right">Name of Stock</label>
                        <div class="col-sm-3">
                            <input id="id-fname" name="fname" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-fname" class="col-sm-3 col-form-label text-right">Number of Shares</label>
                        <div class="col-sm-3">
                            <input id="id-fname" name="fname" type="text" class="form-control" placeholder="" required="">
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<div class="form-check form-check-inline">--}}
                    {{--<input class="form-check-input" type="checkbox" id="same-as">--}}
                    {{--<label class="form-check-label" for="same-as">Additional Stock</label>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <div class="add-more">
                            <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add more</a>
                        </div>
                    </div>

                    <div class="form-group form-group-title">
                        <span>Other Contributions</span>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="same-as">
                            <label class="form-check-label" for="same-as">Contribute restricted stock, shares held at the company/transfer agent, private placements, stock from divi-dend reinvestment plans (DRIPs), or real estate.</label>
                        </div>
                    </div>


                    <div class="form-btn-bar">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <div id="id-next-btn">
                                    <a href="/donor/pools" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
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
