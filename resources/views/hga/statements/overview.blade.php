<?php
$info = $groups['info'];
$holdingSummary = $groups['holding_summary'];
$activitySummary = $groups['activity_summary'];
$holdings = $groups['holdings'];
$contributions = $groups['contributions'];
$distributions = $groups['distributions'];
$income = $groups['income'];
$profile = \App\Models\Contact::sessionContact();
?>

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    <link href="/css/hga/statement.css" rel="stylesheet">

    @include('common.page-header', ['pageTitle' => ''])

    <section class="content">
        <div class="container" style="max-width-X: 100%;">
            <div class="form-wrapper form-last">
                <div class="row mb-3" style="overflow-x: auto;">

                    <div class="col-lg-8 col-r-15">

                        <div class="" style="min-width-X: 710px;">

                            <div class="row row-page-title">
                                <div class="col-12">
                                    <div class="page-title">
                                        <h1>DONOR-ADVISED FUND</h1>
                                        {!! Form::open( ['method' => "GET", 'files' => false, 'id' => 'form-date', 'class' => '' ]) !!}
                                        <div style="display: flex">
                                            <input id="id-date_entered" name="date_entered" type="text" class="form-control mr-sm-2" placeholder="mm/dd/yyyy">
                                            <button type="submit" class="btn btn-theme mb-2 js_on_submit_filter">Go</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>

                            <!-- info -->
                            <table class="fs-table-header">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="fund-contact">
                                            <p>{{ $profile->name }}<br>
                                                {!! $profile->getTwoLineAddress() !!}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact">
                                            <p><span>HIGHGROUND ADVISORS</span><br>1717 Main Street, Suite 1400<br>Dallas, TX
                                                75201<br>214.978.3300 (main)<br>800.747.5564 (toll-free)<br>highgroundadvisors.org
                                            </p>

                                            <p><span>YOUR CONTACT:</span><br>
                                                {{ $info['contact_name'] }}<br>
                                                {{ $info['contact_phone'] }}<br>
                                                {{ $info['contact_email'] }}
                                            </p>

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="fund-name">{{ $info['fund_name'] }}</p>
                                        <p class="fund-id">Account ID: {{ $info['account_id'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="st-period">Statement Period {{$info['from_date']}} through {{$info['thru_date']}}</div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

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
                                    <th><p>BEGINNING BALANCE {{$info['from_date']}}</p></th>
                                    <th><p>ENDING BALANCE {{$info['thru_date']}}</p></th>
                                    <th><p>PERCENT OF HOLDINGS</p></th>
                                </tr>
                                @foreach($holdingSummary['rows'] as $row)
                                    <tr>
                                        <td>{{$row['name']}}</td>
                                        <td>{{$row['begin']}}</td>
                                        <td>{{$row['end']}}</td>
                                        <td>{{$row['percent'] . '%'}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td class="border-top-bottom">{{$holdingSummary['total_begin']}}</td>
                                    <td class="border-top-bottom">{{$holdingSummary['total_end']}}</td>
                                    <td class="border-top-bottom">{{$holdingSummary['total_percent'] . '%'}}</td>
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

                                @foreach($activitySummary['rows'] as $row)
                                    <tr>
                                        <td>{{$row['name']}}</td>
                                        <td>{{$row['amount']}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>{{$activitySummary['total_name']}}</td>
                                    <td class="border-top-bottom">{{$activitySummary['total_amount']}}</td>
                                </tr>

                                {{--<td class="border-bottom">28.34%</td>--}}
                                {{--<td class="border-bottom-thick">$525,480.76</td>--}}

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

                                @foreach($holdings['rows'] as $row)
                                    <tr>
                                        <td>{{$row['name']}}</td>
                                        <td>{{$row['share']}}</td>
                                        <td>{{$row['price']}}</td>
                                        <td>{{$row['value']}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3">GRAND TOTAL</td>
                                    <td class="border-top-bottom">{{$holdings['total']}}</td>
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

                                @if(count($contributions['rows']))
                                    <tr>
                                        <th><p>DATE</p></th>
                                        <th><p>DESCRIPTION</p></th>
                                        <th><p>AMOUNT</p></th>
                                    </tr>

                                    @foreach($contributions['rows'] as $row)
                                        <tr>
                                            <td>{{$row['date']}}</td>
                                            <td>{{$row['description']}}</td>
                                            <td>{{$row['amount']}}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="border-top-bottom">{{$contributions['total']}}</td>
                                    </tr>
                                @else
                                    <tr><td>NO CONTRIBUTION DETAILS AVAILABLE</td></tr>
                                @endif

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

                                @if(count($distributions['rows']))
                                    <tr>
                                        <th><p>DATE</p></th>
                                        <th><p>NAME OF ORGANIZATION</p></th>
                                        <th><p>DESIGNATION</p></th>
                                        <th><p>AMOUNT</p></th>
                                    </tr>

                                    @foreach($distributions['rows'] as $row)
                                        <tr>
                                            <td>{{$row['date']}}</td>
                                            <td>{{$row['grantee']}}</td>
                                            <td>{{$row['description']}}</td>
                                            <td>{{$row['amount']}}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td>GRAND TOTAL</td>
                                        <td></td>
                                        <td></td>
                                        <td class="border-top-bottom">{{$distributions['total']}}</td>
                                    </tr>
                                @else
                                    <tr><td colspan="4">NO DISTRIBUTION DETAILS AVAILABLE</td></tr>
                                @endif

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

                                @foreach($income['rows'] as $row)
                                    <tr>
                                        <td>{{$row['name']}}</td>
                                        <td>{{$row['amount']}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>{{$income['total_name']}}</td>
                                    <td class="border-top-bottom">{{$income['total_amount']}}</td>
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

                        </div>

                        @if(false and \App\Helpers\GnUtils::isDonorSession())
                            <div style="text-align: right">
                                <p style="font-size: 14px">
                                    <a href="{{route('donor-fund-performance', $fund->fund_id)}}">
                                        <i class="fas fa-hand-point-right"></i> Fund Performance</a><br>
                                    <a href="{{route('donor-pool-performance', ['fund_id' => $fund->fund_id, 'type'=>'stp'])}}">
                                        <i class="fas fa-hand-point-right"></i> Pool Performance</a>
                                </p>
                            </div>
                        @endif

                    </div>

                    <div class="col-xl-4 col-md-6 col-l-15">
                        @include('donor.fund-composition', ['id' => $fund->fund_id])
                    </div>
                </div>

                <div class="row" style="overflow-x: auto;">
                    @include('donor.statements._actions')
                </div>

            </div>
        </div>
    </section>


    <script>
        $(function () {
            $("#id-date_entered").daterangepicker({
                singleDatePicker: true,
            });
        });
    </script>

@endsection
