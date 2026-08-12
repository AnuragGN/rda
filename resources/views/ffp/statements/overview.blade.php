@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Fund Overview'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">

                        <h2 class="page-title uppercase mt-3">
                            <span>
                            {{ $fund->fund_name }}
                                <span class="date">{{\App\Helpers\GnUtils::customDate($fund->statement_date)}}</span>
                            </span>
                        </h2>

                        @forelse($groups as $i => $group)
                            @include("donor.statements.group", ['group' => $group, 'groupIndex' => $i])
                        @empty
                            @include("utils.data-not-found", [])
                        @endforelse

                        <br>
                        <hr>
                        @include('donor.statements._actions')

                    </div>

                    <div class="col-lg-4 col-lg">
                        @include('donor.fund-composition', ['id' => $fund->fund_id])
                    </div>

                    {{--<div class="col-lg-4">--}}
                    {{--@if(\App\Helpers\GnUtils::isDonorSession())--}}
                    {{--@include('gna.statements._charts')--}}
                    {{--@endif--}}
                    {{--<!-- @include('pane-placeholder') -->--}}
                    {{--</div>--}}

                </div>
            </div>
        </div>
    </section>

@endsection
