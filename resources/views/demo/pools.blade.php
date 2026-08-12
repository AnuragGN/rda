@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            @include('demo.progress', ['page' => 4])

            <div class="row">
                <div class="col-8">
                    <br />
                    <form id="id-form-primary">

                        <div class="form-group">
                            <p class="form-title">Investment Pools</p>
                            <p class="">Please select maximum of three pools</p>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-5 col-form-label label-bold">Select Funds</label>
                            <label class="col-md-5 col-form-label text-right label-bold">% of total</label>
                        </div>

                        {{-- 1 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-1">
                                    <label class="form-check-label auth-label" for="id-pool-1">
                                        Money Market
                                        <br><small>Stable value earning money-market-fund rates</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-1-value" name="pool-1-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 2 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-2">
                                    <label class="form-check-label auth-label" for="id-pool-2">
                                        Fixed Income
                                        <br><small>Short-duration, investment-grade, fixed-income investments</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-2-value" name="pool-2-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 3 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-3">
                                    <label class="form-check-label auth-label" for="id-pool-3">
                                        Moderate Income – 20% Equity
                                        <br><small>Cautious equity mix for 3+ year holding periods</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-3-value" name="pool-3-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-4">
                                    <label class="form-check-label auth-label" for="id-pool-4">
                                        Balanced Income – 35% Equity
                                        <br><small>Cautious equity mix for 5+ year holding periods</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-4-value" name="pool-4-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-5">
                                    <label class="form-check-label auth-label" for="id-pool-5">
                                        Balanced Growth – 50% Equity
                                        <br><small>Moderate equity mix for 5+ year holding periods</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-5-value" name="pool-5-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        {{-- 6 --}}
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check form-check-inline form-check-dl">
                                    <input type="checkbox" class="form-check-input" id="id-pool-6">
                                    <label class="form-check-label auth-label" for="id-pool-6">
                                        Growth – 70% Equity
                                        <br><small>Highest-risk equity mix for long-term holding periods (7+ years)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="pool-value">
                                    <input id="id-pool-6-value" name="pool-6-value" type="text" class="form-control" placeholder="" required="">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-10 col-form-label text-right">
                                <span class="label-bold">Total x%</span>
                                <br><span class="label-note">Total must equal 100%</span>
                            </label>
                        </div>

                        <div class="form-btn-bar text-center">
                            <div id="id-next-btn" class="col-12 form-footer">
                                <p class="action"><a href="/registration/signature" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                                <p class="completed th-color hide">80% COMPLETED</p>
                            </div>
                        </div>

                    </form>

                </div>


            <div class="col-md-4">
                <br />
                <br />
                <br />
                <div class="info-card mb-3">
                    <p>
                        Donors may select up to three investment pools in percentages to total 100%.
                        If no investment pool is selected, your contributions will be invested in the Money Market.</p>
                </div>
            </div>

            </div>

        </div>

    </div>

@endsection
