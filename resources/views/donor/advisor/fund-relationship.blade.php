@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => "Fund Advisors"])

    <style>
        .fund-advisors .advisor-name {
            font-weight:600;
            font-size: 110%;
        }
        .fund-advisors label {
            font-weight: 600!important;
            font-size: 90%;
            margin: 0;
            color: #646464;
        }
    </style>
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-9 fund-advisors">

                    @foreach($items as $fund => $info)
                        <h3 class="page-subtitle uppercase mt-2">{{$fund}}</h3>
                        <div class="row">
                                @foreach($info as $item)
                                <div class="col-sm-6">
                                    <div class="gn-card card-fund-item gn-shadow">
                                        <span class="advisor-name">{{ $item['contact_name'] }}</span>
                                        <br><label>Email:</label><span> {{$item['contact_email']}}</span>
                                        {{--<br><span>{{$item['fund_name']}}</span>--}}
                                        <br><label>Grant Recommendations:</label> <span>{{$item['make_grant'] ? 'Enabled' : 'Disabled'}}</span>
                                        {{--<br><span>{{$item['viewable']}}</span>--}}
                                        <br><label>Relationship to Fund:</label> <span>{{$item['relationship']}}</span>
                                    </div>
                            </div>
                            @endforeach
                        </div>
                        <br>
                    @endforeach

                </div>

                <div class="col-xl-3"></div>

            </div>
        </div>
    </div>

@endsection
