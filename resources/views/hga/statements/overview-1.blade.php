{{--@extends ('layouts.console-full')--}}
@extends ('donor.layouts.main')

@section ('content')

    <div class="row">

        <div class="col-lg-8">

            <div class="gn-breadcrumbs">
                @include('donor.common.breadcrumbs')
            </div>

            <div class="row row-page-title">
                <div class="col-12">
                    <h1 class="page-title uppercase">
                        <div>{{ $fund->fund_name }}
                        <span style="font-size: 16px; color: #999; text-transform: capitalize;font-weight: 600;">{{\App\Helpers\GnUtils::customDate($fund->thru_date)}}</span></div>
                    </h1>
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

@endsection

