@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    {{--hero--}}
    <div class="container-fluid">
        <div class="row row-hero">
            <div class="hero-box">
                <img src="/images/demo/hero.jpg" alt="">
                <div class="caption">
                    <div class="container">
                        <div class="left">
                            <span class="cap-title">Welcome, Chris!</span><br>
                            <span class="cap-info">AVAILABLE TO GRANT: $98,201</span><br>
                            <span class="cap-info">$XX,XXX.XX</span>
                        </div>
                        <div class="cta">
                            <ul class="">
                                <li><a href="#" class="btn btn-hga-md btn-white">FUND ACCOUNT</a></li>
                                <li><a href="#" class="btn btn-hga-md btn-white">MAKE A GRANT</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row ">

            <div class="col-3">
                @include('demo.side-menu')
            </div>

            <div class="col-9">

                <div class="row row-page-title">
                    <div class="col-12">
                        <h1 class="page-title">The Smith Family Fund</h1>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <span style="font-weight: 300">Financial Statement for the Period
                            <span style="font-weight: 600">January through March 2020</span>
                            <br>Your Contact is: <span style="font-weight: 600">Joseph Brooks</span>
                        </span>
                    </div>
                </div>
                <br>


                <div class="row">
                    <div class="col-12">
                        <div class="fund-pool">

                            <div class="pool-kv">
                                    <span class="name">
                                        <a href="javascript:void(0);" class="toggle-icon js_toggle_pool_values" title="Expand / Minimize" data-target-id="id-pool-0-0">
                                            <small><i class="fas fa-minus-circle"></i><i class="fas fa-plus-circle hide"></i></small>
                                        </a> Total Beginning Fund Balance</span>
                                <span class="amount"> 7,040,609.20</span>
                            </div>

                            <div class="pool-values" id="id-pool-0-0">
                                <div class="fund-kv">
                                    <span>Principle</span>
                                    <span>7,061,550.46</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Grants Payable</span>
                                    <span>-</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Income available to spend from prior years</span>
                                    <span>(20,941.26)</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Cash surrender value of life insurance policy</span>
                                    <span>-</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="fund-pool">

                            <div class="pool-kv">
                                <span class="name">
                                    <a href="javascript:void(0);"
                                       class="toggle-icon js_toggle_pool_values"
                                       title="Expand / Minimize" data-target-id="id-pool-0-1">
                                        <small>
                                            <i class="fas fa-minus-circle"></i>
                                            <i class="fas fa-plus-circle hide"></i>
                                        </small>
                                    </a> Total Additions
                                </span>
                                <span class="amount"> 1,644,384.54</span>
                            </div>

                            <div class="pool-values" id="id-pool-0-1">
                                <div class="fund-kv">
                                    <span>Contributions</span>
                                    <span>826,976.41</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Other interest and dividends</span>
                                    <span>-</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Gains/losses-stock gifts or other assets</span>
                                    <span>(83.98)</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Net activity of investment pool</span>
                                    <span>817,576.09</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Other additions</span>
                                    <span>(83.98)</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="fund-pool">

                            <div class="pool-kv">
                <span class="name">
                    <a href="javascript:void(0);" class="toggle-icon js_toggle_pool_values" title="Expand / Minimize" data-target-id="id-pool-0-2">
                        <small><i class="fas fa-minus-circle"></i><i class="fas fa-plus-circle hide"></i></small>
                    </a> Total Distributions</span>
                                <span class="amount"> (460,405.19)</span>
                            </div>

                            <div class="pool-values" id="id-pool-0-2">
                                <div class="fund-kv">
                                    <span>Grants approved</span>
                                    <span>430,300.00</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Administrative management fees</span>
                                    <span>-</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Other distributions</span>
                                    <span>-</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="fund-pool">

                            <div class="pool-kv">
                                    <span class="name">
                                        <a href="javascript:void(0);" class="toggle-icon js_toggle_pool_values" title="Expand / Minimize" data-target-id="id-pool-0-3">
                                            <small><i class="fas fa-minus-circle"></i><i class="fas fa-plus-circle hide"></i></small>
                                        </a> Total Ending Fund Balance</span>
                                <span class="amount"> 8,224,588.55</span>
                            </div>

                            <div class="pool-values" id="id-pool-0-3">
                                <div class="fund-kv">
                                    <span>Ending Fund Balances</span>
                                    <span>8,250,262.27</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Grants Outstanding</span>
                                    <span>-</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Income available to spend</span>
                                    <span>(25,589.74)</span>
                                </div>

                                <div class="fund-kv">
                                    <span>Cash surrender value of life insurance policy</span>
                                    <span>-</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="fund-st-info">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dignissim aliquam magna eu vehicula. Aenean tincidunt tincidunt augue vel tristique. Maecenas scelerisque elit diam, ut commodo dui tempor vel. Fusce a ullamcorper dolor. Sed ornare dui mi, sit amet efficitur dolor malesuada a.</div>

                <div class="projected">
                <div class="row row-page-subtitle">
                    <div class="col-12">
                        <div class="page-subtitle">
                            <h2>Projected Income Available for Grants</h2>
                        </div>
                    </div>
                </div>


                <div class="fund-kv">
                    <span>Income available to spend as of 11/30/2019</span>
                    <span>(25,589.74)</span>
                </div>

                <div class="fund-kv">
                    <span>Grant payments scheduled for 2019</span>
                    <span>-</span>
                </div>

                <div class="fund-kv">
                    <span>Projected net income for 12/1/2019 to 12/31/2019</span>
                    <span>27,657.47</span>
                </div>

                <div class="fund-kv">
                    <span>Total projected net income available for grants through 12/31/2019</span>
                    <span>2,067.73</span>
                </div>
                </div>

                <br>
                <hr>
                <div class="st-btn-bar">
                    <a href="javascript:void(0);" class="btn btn-theme">Grant History</a>
                    <a href="javascript:void(0);" class="btn btn-theme">Gift History</a>
                    <a href="javascript:void(0);" class="btn btn-theme">Make a Grant</a>
                </div>

            </div>

        </div>

    </div>

@endsection
