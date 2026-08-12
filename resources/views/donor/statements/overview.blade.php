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
                        <span>
                            {{ $fund->fund_name }}
                            <span class="date">{{\App\Helpers\GnUtils::customDate($fund->thru_date)}}</span>
                        </span>
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

