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
                                <li><a href="#" class="btn btn-info btn-hga-md btn-white">FUND ACCOUNT</a></li>
                                <li><a href="#" class="btn btn-info btn-hga-md btn-white">MAKE A GRANT</a></li>
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
                        <h1 class="page-title">Dashboard</h1>
                    </div>
                </div>

                <div class="row row-page-subtitle">
                    <div class="col-12">
                        <h3 class="page-subtitle">Funds</h3>
                    </div>
                </div>

                <div class="row row-fund-overview">
                    <div class="col-12">
                        <div class="card-fund-item">
                            <div class="header">
                                <p class="card-title">The Smith Family Fund</p>
                                <h5 class="card-title card-title-amount">$6,382.54</h5>
                            </div>
                            <div class="actions">
                                <a href="/donor/statement" class="btn btn-sm btn-theme">Fund Overview</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Grant History</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Gift History</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Make a Grant</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row row-fund-overview">
                    <div class="col-12">
                        <div class="card-fund-item">
                            <div class="header">
                                <p class="card-title">John's Fund</p>
                                <h5 class="card-title card-title-amount">$6,566.47</h5>
                            </div>
                            <div class="actions">
                                <a href="/donor/statement" class="btn btn-sm btn-theme">Fund Overview</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Grant History</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Gift History</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-theme">Make a Grant</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
