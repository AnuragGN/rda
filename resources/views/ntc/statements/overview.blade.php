@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    <style>
        table.nif-st-balance {
            width: 100%;
        }
        table.nif-st-balance tr td:nth-child(2){
            text-align: right;
            font-weight: 600;
        }
    </style>
    @include('common.page-header', ['pageTitle' => $custom->text->FUND_OVERVIEW])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">

                <div class="col-lg-8 col-r-15">

                    <div class="row row-page-title">
                        <div class="col-12">
                            <div class="page-title mt-3">
                                <h3>{{ $fund->fund_name }} <span class="fund-subtype">({{$fund->fund_subtype}})</span></h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <span>
                                As on <span style="font-weight: 600">{{ \App\Helpers\GnUtils::customDate($fund->thru_date) }} </span>
                                <br>Fund Id: <span style="font-weight: 600">{{ $fund->fund_id }}</span>
                                <br>Fund Code: <span style="font-weight: 600">{{ $fund->account_number }}</span>
                            </span>
                        </div>
                    </div>

                    <hr>

                    @if(count($groups))
                        @include("nif.statements.groups", ['groups' => $groups])
                    @else
                        @include("utils.data-not-found", [])
                    @endif

                    <br>
                    <div class="row">
                        <div class="offset-md-7 col-md-5">
                            <table class="nif-st-balance">
                                @if($fund->cash)
                                    <tr>
                                        <td>Cash</td>
                                        <td>{{ $fund->cash }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>Ending Balance</td>
                                    <td>{{ $fund->ending_balance }}</td>
                                </tr>
                                <tr>
                                    <td>End Total Balance</td>
                                    <td>{{ $fund->end_total_bal }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <br>
                    <hr>
                    @include('donor.statements._actions')

                </div>

                <div class="col-lg-4">
                    @include('pane-placeholder')
                </div>

            </div>
        </div>
    </div>


@endsection
