@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Fund Overview'])

        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">

                        @include('jcf.statements.overview-filters')

                        <h2 class="page-title uppercase mt-3">
                            <div>
                                {{ $fund->fund_name }} <span class="date">Market Value as of {{\App\Helpers\GnUtils::customDate($fund->statement_date)}}</span>
                            </div>
                            <div class="dropdown" style="text-transform: none!important">
                                <button class="btn btn-light btn-sm shadowed dropdown-toggle" type="button" id="print-btn"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-print"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="print-btn">
                                    <a class="dropdown-item"
                                       href="{{ route('fund', ['id' => $fund->fund_id, 'date' => $date, 'print' => 'summary']) }}"
                                       target="_blank">Print Summary</a>
                                    <a class="dropdown-item"
                                       href="{{ route('fund', ['id' => $fund->fund_id, 'date' => $date, 'print' => 'full']) }}"
                                       target="_blank">Print Full Statement</a>
                                </div>
                            </div>

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
                        @if (isset($fundComposition))
                            @include('donor.fund-composition', ['id' => $fund->fund_id])
                            @include('agency.performance.returns-summary', ['accountId' => $fund->fund_id, 'accountType' => 'fund'])
                        @endif
                    </div>

                </div>
            </div>
        </div>

@endsection

