@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            @include('demo.progress', ['page' => 3])

            <div class="row">
                <div class="col-8">
                    <br />
                    <form id="id-form-primary">
                        <div class="form-group">
                            <p class="form-title">Contributions</p>
                        </div>

                        <div class="form-group mt-4">
                            <p class="form-subtitle">Cash Equivalents</p>
                        </div>


                        {{-- ACH --}}
                        <div class="form-group row">
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"  checked type="checkbox" id="id-ach">
                                    <label class="form-check-label" for="id-ach">ACH</label>
                                </div>
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label text-right">ACH Amount</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label text-right">Bank Name</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>

                        </div>

                        {{-- WIRE --}}
                        <div class="form-group row">
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" checked type="checkbox" id="id-wire">
                                    <label class="form-check-label" for="id-wire">Wire</label>
                                </div>
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label text-right">Wire Amount</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label text-right">Bank Name</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        {{-- Check --}}
                        <div class="form-group row">
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" checked type="checkbox" id="id-check">
                                    <label class="form-check-label" for="id-check">Check</label>
                                </div>
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label pl-0 text-right">Check Amount</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>

                            <label for="id-dollars" class="col-md-2 col-form-label text-right">Bank Name</label>
                            <div class="col-md-3 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <br>
                        <div class="form-group">
                            <p class="form-subtitle">Securities or Mutual Funds</p>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" checked type="checkbox" id="id-snmf">
                                <label class="form-check-label" for="id-snmf">Contribute securities or mutual funds</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-fund-name" class="col-md-3 col-form-label form-multi-line-label text-right">Security/Mutual Fund Name</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-fund-name" name="fund-name" type="text" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-symbol" class="col-md-3 col-form-label text-right">Name</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-symbol" name="symbol" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-custodian" class="col-md-3 col-form-label form-multi-line-label text-right">Custodian Account Number</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-custodian" name="custodian" type="text" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-no-shares" class="col-md-3 col-form-label text-right">Number of Shares</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-no-shares" name="no-shares" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id-dollars" class="col-md-3 col-form-label form-multi-line-label text-right">Approx. Dollar Amount</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-dollars" name="dollars" type="text" class="form-control" placeholder="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="add-more">
                                <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add more</a>
                            </div>
                        </div>

                        <div class="form-btn-bar text-center">
                            <div id="id-next-btn" class="form-footer">
                                <p class="action"><a href="/registration/pools" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="col-4">
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <div class="info-card">
                        <p>FOR CASH ACH DEPOSITS:
                            <br>Bank of America, Morristown NJ
                            <br>ABA# 111000111
                            <br>For credit to OAKWOOD Foundation
                            <br>Account# 0181181181
                            <br>Ref: For the benefit of: __________________</p>

                        <p>FOR CASH WIRE DEPOSITS:
                            <br>Bank of America, Morristown NJ
                            <br>ABA# 026026026
                            <br>For credit to OAKWOOD Foundation
                            <br>Account# 0181181181
                            <br>Ref: For the benefit of: __________________</p>

                        <p>FOR CHECK DEPOSITS:
                            <br>OAKWOOD Foundation
                            <br>P.O. Box 840840
                            <br>Morristown, NJ 07960-0330</p>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
