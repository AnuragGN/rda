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
                        <h1 class="page-title">DONOR-ADVISED FUND</h1>
                    </div>
                </div>

                <table class="fs-table-header">
                    <tbody>
                    <tr>
                        <td>
                            <div class="fund-contact">
                                <p>Joe and Donna Jones<br>
                                    143 S. 3rd St.<br>
                                    Dallas, TX 75226</p>
                            </div>
                        </td>
                        <td>
                            <div class="contact">
                                <p><span>HIGHGROUND ADVISORS</span><br>1717 Main Street, Suite 1400<br>Dallas, TX
                                    75201<br>214.978.3300 (main)<br>800.747.5564 (toll-free)<br>highgroundadvisors.org
                                </p>

                                <p><span>YOUR CONTACT:</span><br>Jane
                                    Smith<br>214.978.3318<br>client.support@highgroundadvisors.org
                                </p>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="fund-name">THE JOE & DONNA JONES FAMILY FUND</p>

                            <p class="fund-id">Account ID: 18981</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="st-period">Statement Period Jan 01, 2019 through Mar 31, 2019</div>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <style>
                </style>

                <!-- holdings summary -->
                <table class="fs-table fs-table-hs">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">HOLDINGS SUMMARY</td>
                    </tr>

                    <colgroup>
                        <col class="hd_col_a">
                        <col class="hd_col_b">
                        <col class="hd_col_c">
                        <col class="hd_col_d">
                    </colgroup>

                    <tr>
                        <th><p>ASSET DESCRIPTION</p></th>
                        <th><p>BEGINNING BALANCE 01/01/2019</p></th>
                        <th><p>ENDING BALANCE 03/31/2019</p></th>
                        <th><p>PERCENT OF HOLDINGS</p></th>
                    </tr>
                    <tr>
                        <td>HGA DAF</td>
                        <td>6,078.8500</td>
                        <td>1.000000</td>
                        <td>78.85%</td>
                    </tr>
                    <tr>
                        <td>WELLS FARGO & COMPANY MATURE 06/12/2048</td>
                        <td>6,078.8500</td>
                        <td>1.000000</td>
                        <td>78.85%</td>
                    </tr>
                    <tr>
                        <td>PROCTER & GAMBLE – DUE 09/15/2058</td>
                        <td class="border-bottom">$148,891.50</td>
                        <td class="border-bottom">$148,891.50</td>
                        <td class="border-bottom">28.34%</td>
                    </tr>
                    <tr>
                        <td>WELLS FARGO & COMPANY MATURE 06/12/2048</td>
                        <td class="border-bottom">$148,891.50</td>
                        <td class="border-bottom">$148,891.50</td>
                        <td class="border-bottom">28.34%</td>
                    </tr>
                    <tr>
                        <td>TOTAL HOLDINGS AT END OF PERIOD</td>
                        <td class="border-bottom-thick">$148,891.50</td>
                        <td class="border-bottom-thick">$148,891.50</td>
                        <td class="border-bottom-thick">28.34%</td>
                    </tr>

                    </tbody>

                </table>

                <!-- activity summary -->
                <table class="fs-table fs-table-as">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">ACTIVITY SUMMARY</td>
                    </tr>

                    <colgroup>
                        <col class="as_col_a">
                        <col class="as_col_b">
                    </colgroup>

                    <tr>
                        <td>BEGINNING BALANCE</td>
                        <td>$6,078.8500</td>
                    </tr>
                    <tr>
                        <td>GRANT DISTRIBUTIONS</td>
                        <td>($6,078.8500)</td>
                    </tr>
                    <tr>
                        <td>CHANGE IN MARKET VALUE</td>
                        <td class="border-bottom">28.34%</td>
                    </tr>
                    <tr>
                        <td>ENDING BALANCE</td>
                        <td class="border-bottom-thick">$525,480.76</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="footnote">
                            * Net investment income may include activity such as dividend and interest income, realized
                            gains and losses, gain or loss on sale of contributed securities, administrative fees and
                            expenses.
                        </td>
                    </tr>
                    </tbody>

                </table>

                <!-- holding details -->
                <table class="fs-table fs-table-hd">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">HOLDINGS DETAILS</td>
                    </tr>

                    <colgroup>
                        <col class="hd_col_a">
                        <col class="hd_col_b">
                        <col class="hd_col_c">
                        <col class="hd_col_d">
                    </colgroup>

                    <tr>
                        <th><p>ASSET DESCRIPTION</p></th>
                        <th><p>SHARES</p></th>
                        <th><p>MARKET PRICE</p></th>
                        <th><p>MARKET VALUE</p></th>
                    </tr>
                    <tr>
                        <td>CASH</td>
                        <td>6,078.8500</td>
                        <td>1.000000</td>
                        <td>$6,078.85</td>
                    </tr>
                    <tr>
                        <td>WELLS FARGO & COMPANY MATURE 06/12/2048</td>
                        <td>6,078.8500</td>
                        <td>1.000000</td>
                        <td>$6,078.85</td>
                    </tr>
                    <tr>
                        <td colspan="3">GRAND TOTAL</td>
                        <td class="border-top-bottom">$6,078.85</td>
                    </tr>

                    </tbody>

                </table>

                <!-- contribution details -->
                <table class="fs-table fs-table-cd">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">CONTRIBUTION DETAILS</td>
                    </tr>

                    <colgroup>
                        <col class="cd_col_a">
                        <col class="cd_col_b">
                        <col class="cd_col_c">
                    </colgroup>

                    <tr>
                        <th><p>DATE</p></th>
                        <th><p>CONTRIBUTION DESCRIPTION</p></th>
                        <th><p>VALUE RECEIVED</p></th>
                    </tr>
                    <tr>
                        <td>02/20/2019</td>
                        <td>CASH</td>
                        <td>6,078.8500</td>
                    </tr>
                    <tr>
                        <td>03/06/2019</td>
                        <td>1000.0000sh PG@90.050000<br>PROCTER & GAMBLE COMMON</td>
                        <td>6,078.8500</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td class="border-top-bottom">$6,078.85</td>
                    </tr>

                    </tbody>

                </table>

                <!-- grant distribution details -->
                <table class="fs-table fs-table-gdd">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">GRANT DISTRIBUTION DETAILS</td>
                    </tr>

                    <colgroup>
                        <col class="gdd_col_a">
                        <col class="gdd_col_b">
                        <col class="gdd_col_c">
                        <col class="gdd_col_d">
                    </colgroup>

                    <tr>
                        <th><p>DATE</p></th>
                        <th><p>NAME OF ORGANIZATION</p></th>
                        <th><p>DESIGNATION</p></th>
                        <th><p>AMOUNT</p></th>
                    </tr>
                    <tr>
                        <td>01/06/2019</td>
                        <td>FIRST BAPTIST CHURCH - DALLAS</td>
                        <td>CAPITAL CAMPAIGN</td>
                        <td>($1,250.00)</td>
                    </tr>
                    <tr>
                        <td>01/27/2019</td>
                        <td>FIRST BAPTIST CHURCH - DALLAS</td>
                        <td>FOR THE MISSION FUND</td>
                        <td>($3,600.00)</td>
                    </tr>
                    <tr>
                        <td colspan="3">GRAND TOTAL</td>
                        <td class="border-top-bottom text-right">$6,078.85</td>
                    </tr>

                    </tbody>

                </table>


                <!-- net investment income details -->
                <table class="fs-table fs-table-nii">
                    <tbody>
                    <tr>
                        <td colspan="4" class="header">NET INVESTMENT INCOME DETAILS</td>
                    </tr>

                    <colgroup>
                        <col class="nii_col_a">
                        <col class="nii_col_b">
                    </colgroup>

                    <tr>
                        <td>INTEREST INCOME</td>
                        <td>$6,078.85</td>
                    </tr>
                    <tr>
                        <td>CAPITAL GAIN DISTRIBUTION</td>
                        <td>$6,078.85</td>
                    </tr>
                    <tr>
                        <td>REALIZED GAIN/LOSS</td>
                        <td>($78.85)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="border-top-bottom">$525,480.76</td>
                    </tr>

                    </tbody>

                </table>


                <div class="row hide">
                    <div class="col-12">
                        <span style="font-weight: 300">Financial Statement for the Period
                            <span style="font-weight: 600">January through March 2020</span>
                            <br>Your Contact is: <span style="font-weight: 600">Joseph Brooks</span>
                        </span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 st-footer-note">
                        <p>***Important Statement Information***<br>
                            Clients are responsible for monitoring and reviewing information included on statements and
                            other reports provided.<br>
                            If a discrepancy is identified, it should be reported to HighGround Advisors within 90 days
                            of the statement ending date.</p>
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
