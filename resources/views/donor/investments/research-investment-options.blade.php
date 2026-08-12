@extends('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Research Investment Options'])
    <div class="container">
        <div class="form-wrapper form-last">

            <div class="form-group row">
                <div class="col-3">
                    <h5>AS OF MARCH 31, 2022</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <h5>INVESTMENT OPTION NAME</h5>
                </div>
                <div class="col-4">
                    <h5>APPROXIMATE ASSET ALLOCATION</h5>
                </div>
                <div class="col-2">
                    <h5>EXPECTED GIVING TIME HORIZON</h5>
                </div>
                <div class="col-2">
                    <h5>EXPENSE RATIOS</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-12 clr-primary text-white pt-2 pb-2">
                    <div class="row">
                        <div class="col-4">HIGHGROUND ENHANCED CASH FUND</div>
                        <div class="col-4">100% Cash</div>
                        <div class="col-2">< 2 YEARS</div>
                        <div class="col-2">0.38%</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 ml-2 mr-2 mb-3">
               HighGround Enhanced Cash Fund provides diversified investment exposure to U.S. investment grade money market and short to medium-term fixed income securities, including U.S. Treasury, mortgage, corporate and municipal bonds. The Fund is managed as a fund-of-funds and seeks to earn current yields in excess of money market rates, while also providing preservation of principal, daily liquidity and a constant net asset value. The Fund is constructed by combining a prime money market portfolio and a high quality, higher yielding, low duration fixed income portfolio. The fixed income portfolio has credit quality, duration and sector exposures similar to the ICE BofAML Corporate & Government 1-3 Year Index. This investment option is used within donor-advised funds for individuals with a grant-making time horizon of two years or less.
            </div>

            <div class="row mt-4">
                <div class="col-12 clr-primary text-white pt-2 pb-2">
                    <div class="row">
                        <div class="col-4">HIGHGROUND CONSERVATIVE FUND</div>
                        <div class="col-4">20% Growth, 50% Fixed, 30% Cash</div>
                        <div class="col-2">1 − 5 YEARS</div>
                        <div class="col-2">0.47%</div>
                    </div>
                </div>

            </div>

            <div class="mt-4 ml-2 mr-2 mb-3">
                <div class="mt-2">This Fund seeks to employ a globally diversified, multi-asset class strategy with a risk and return profile that aligns with a short-term grant-making time horizon of five years or less. The Fund’s investments typically include U.S. domestic equities, U.S. domestic fixed income and cash. The Fund will generally have the following key strategic attributes:<br></div>
                <div class="ml-3">
                    — Significant allocation to precautionary investments expected to protect against macroeconomic shocks of inflation and deflation <br>
                    — Small allocation to equity, which acts as the Fund’s main growth driver<br>
                    — Broad diversification by asset class, geography and sector<br>
                    — Daily liquidity to fund charitable grants
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 clr-primary text-white pt-2 pb-2">
                    <div class="row">
                        <div class="col-4">HIGHGROUND BALANCED FUND</div>
                        <div class="col-4">50% Growth, 40% Fixed, 10% Cash</div>
                        <div class="col-2">5 − 10 YEARS</div>
                        <div class="col-2">0.52%</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 ml-2 mr-2 mb-3">
                <div class="mt-2">This Fund seeks to employ a globally diversified, multi-asset class strategy with a risk and return profile that aligns with a grant-making time horizon of five to ten years. The Fund’s investments typically will include a balanced portfolio of U.S. domestic and international equities, U.S. domestic fixed income, and cash. The Fund will generally have the following key attributes:</div>
                <div class="ml-3">
                    — Allocation to equity, which acts as the Fund’s main growth driver <br>
                    — Allocation to precautionary investments expected to protect against macroeconomic shocks of inflation and deflation <br>
                    — Broad diversification by asset class, geography and sector <br>
                    — Daily liquidity to fund charitable grants <br>
                </div>
            </div>

            <div class="form-group row mt-4">
                <div class="col-12 clr-primary text-white pt-2 pb-2">
                    <div class="row">
                        <div class="col-4">HIGHGROUND GROWTH FUND</div>
                        <div class="col-4 ">70% Growth, 25% Fixed, 5% Cash</div>
                        <div class="col-2">> 10 YEARS</div>
                        <div class="col-2 ">0.76%</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 ml-2 mr-2 mb-3">
                <div class="mt-2">The Fund seeks to employ a globally diversified, multi-asset class strategy with a risk and return profile that aligns with a grant making time horizon of more than 10 years. The Fund’s investments typically include U.S. domestic and international equities, U.S. domestic and global fixed income and cash. The Fund will generally have the following key strategic attributes</div>
                <div class="ml-3">
                    — Significant allocation to equity, which acts as the Fund’s main growth driver <br>
                    — Precautionary investments expected to protect against macroeconomic shocks of inflation and deflation <br>
                    — Broad diversification by asset class, geography and sector <br>
                    — Daily liquidity to fund charitable grants <br>
                </div>
            </div>

            <div class="form-group row mt-4">
                <div class="col-12 clr-primary text-white pt-2 pb-2">
                    <div class="row">
                        <div class="col-4">HIGHGROUND KEYSTONE FUND</div>
                        <div class="col-4">64% Growth, 25% Fixed, 10% Real, 1% Cash</div>
                        <div class="col-2">> 10+ YEARS</div>
                        <div class="col-2">0.83%</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 ml-2 mr-2 mb-3">
                <div class="mt-2">The HighGround Keystone Fund provides a globally diversified, multi-asset class strategy with no exposure to private or alternative investments and is the optimal choice for Donor Advisors who wish to provide an endowment feature within their donor-advised fund. The Fund has a risk and return profile that aligns with the perpetual investment horizon and long-term objectives of endowment assets and is managed as a fund-of-funds using HighGround’s public equity and fixed income investment funds.</div>
            </div>
        </div>

    </div>

@endsection
