{{-- DONOR FUND BALANCE WIDGET --}}

<div class="dashboard-section" id="fund-balance-widget">
    <div class="chart-box mt-2">
        <div class="title">Donor Funds Balances</div>
        <div class="scrollable-content">
            <div class="row">
                @php $i = 1; @endphp
                @foreach ($charities as $charity)
                    @php
                        $open_style = $i == 1 ? '' : 'none';
                        $close_style = $i == 1 ? 'none' : 'inline';
                    @endphp
                    <div class="col-12">
                        <div class="fund-pool pool-default" style="padding: 1rem 1rem;">
                            <a href="{{ route('agency-charity', [$charity['id']]) }}" class="pool-kv">
                                <span class="name">
                                    <span class="js_toggle_pool_values"
                                        data-target-id="fundGrantDiv-{{ $charity['id'] }}"
                                        title="Click to Expand / Collapse">
                                        <small id="id_pool_open" style="display: {{ $open_style }};">
                                            <i class="fas fa-minus-circle toggle-icon"></i>
                                        </small>
                                        <small id="id_pool_closed"
                                            style="display: {{ $close_style }};">
                                            <i class="fas fa-plus-circle toggle-icon"></i>
                                        </small>
                                    </span>
                                    {{ $charity['name'] }}
                                </span>
                                <span class="amount">
                                    ${{ number_format($charity['total_balance'], 2) }}
                                </span>
                            </a>
                            <div class="pool-values" id="fundGrantDiv-{{ $charity['id'] }}"
                                style="display: {{ $open_style }};">
                                @if ($charity['funds']->isNotEmpty())
                                    @foreach ($charity['funds'] as $fund)
                                        <div class="fund-pool">
                                            <a class="pool-kv js_toggle_pool_values" title="">
                                                <span class="name">{{ $fund['name'] }}</span>
                                                <span
                                                    class="amount">${{ number_format($fund['balance'], 2) }}</span>
                                            </a>
                                            <a href="{{ route('agency-fund', [$fund['fund_id']]) }}">
                                                <small><u><i>Fund Overview</i></u></small>
                                            </a>&nbsp;
                                            <a
                                                href="{{ route('agency-grant-history', [$fund['fund_id']]) }}">
                                                <small><u><i>Disbursement History</i></u></small>
                                            </a>&nbsp;
                                            <a
                                                href="{{ route('agency-gift-history', [$fund['fund_id']]) }}">
                                                <small><u><i>Contribution History</i></u></small>
                                            </a>&nbsp;
                                            <a
                                                href="{{ route('agency-charity-fund-client', ['id' => $charity['id'], 'fund_id' => $fund['fund_id']]) }}">
                                                <small><u><i>Clients</i></u></small>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div>No funds associated with this charity.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>