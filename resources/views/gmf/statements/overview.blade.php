{{--@extends ('layouts.console-full')--}}
@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Fund Overview'])

    <div class="container">
        <div class="form-wrapper form-last">
    <div class="row">

        <div class="col-lg-8">

            {{--<div class="gn-breadcrumbs">--}}
                {{--@include('donor.common.breadcrumbs')--}}
            {{--</div>--}}

            <div class="row row-page-title">
                <div class="col-12">
                    <div class="page-title">
                        <h3>{{ $fund->fund_name }} <span class="fund-subtype">({{$fund->fund_subtype}})</span>
                            <span class="hide" style="font-size: 16px; color: #999; text-transform: capitalize;font-weight: 600;">
                                {{\App\Helpers\GnUtils::customDate($fund->thru_date)}}
                            </span>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <p style="font-weight: 300">Financial Statement for the Period
                        <span style="font-weight: 600">{{ $fund->beg_month }} through {{ $fund->end_month }} {{ $fund->current_year }}</span>
                        <br>Your Contact is: <span style="font-weight: 600">{{ $fund->staffname }}</span>
                    </p>
                </div>
            </div>

            @forelse($groups as $i => $group)
                @include("donor.statements.group", ['group' => $group, 'groupIndex' => $i])
            @empty
                @include("utils.data-not-found", [])
            @endforelse

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
