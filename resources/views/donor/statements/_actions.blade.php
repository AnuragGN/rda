<div class="st-btn-bar">
    @if(\App\Helpers\GnUtils::isAgencySession())
        <a href="{{ route('agency-grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">
            Disbursement History
        </a>
        <a href="{{ route('agency-gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">
            Contribution History
        </a>
    @else
        <a href="{{ route('grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">
            {{\App\Helpers\GnUtils::isDonorSession() ? 'Grant History' : 'Disbursement History'}}
        </a>
        <a href="{{ route('gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">
            {{\App\Helpers\GnUtils::isDonorSession() ? $custom->text->GIFT_HISTORY : 'Contribution History'}}
        </a>
    @endif

    @if(\App\Helpers\GnUtils::isDonorSession())
        <a href="{{ route('grant-create', ['fund_id' => $fund->fund_id]) }}" class="btn btn-sm btn-theme">{{ $custom->text->MAKE_A_GRANT }}</a>

        @if(\App\Models\GhComposition::compositionExists($fund->fund_id))
            <a href="{{ route('donor-fund-performance', $fund->fund_id) }}" class="btn btn-sm btn-theme">Fund Performance</a>
        @endif

    @elseif(\App\Helpers\GnUtils::isAgencySession())

        @if(\App\Models\GhComposition::compositionExists($fund->fund_id))
            <a href="{{ route('agency-fund-performance', $fund->fund_id) }}" class="btn btn-sm btn-theme">Fund Performance</a>
        @endif

    @endif

</div>

@if(\App\Helpers\GnUtils::isAgencySession() && \App\Models\GhPerformance::isFundCompositionPerformanceAvailable($fund->fund_id))
    <div class="cfp_link">
        <a href="{{ route('agency-fund-performance', ['fund_id' => $fund->fund_id, 'composite' => true]) }}"
           id="id_composite_fund_performance_btn">Composite Fund Performance</a> is available for this fund.
    </div>
@endif
