
{{--@if ($total == 1)--}}
<div class="row row-fund-overview">
    <div class="col-12" style="display: flex; align-items: center; justify-content: space-between">

        <div class="gn-card card-fund-item gn-shadow">
            <div class="header">
                <p class="card-title text-primary-dark">{{ $fund->name }}</p>
                <h5 class="card-title text-accent fw600">

                    @if(\App\Models\ClientInfo::isJCF())
                        <div style="text-align: right">
                            <span style="font-size: 14px; font-weight: normal;">As of {{\App\JCF\JCFStatement::getDisplayableStatementDate($fund)}}<br/></span>
                            <span data-toggle="tooltip"
                                  title="Please note that cash is invested/raised once a week. Therefore you may see a slight delay between when a gift or grant is recorded and when your invested balance is updated.">
                                {{ \App\Helpers\GnUtils::money($fund->getStatementBalance()) }}
                            </span>
                        </div>
                    @else
                        {{ \App\Helpers\GnUtils::money($fund->getStatementBalance()) }}
                    @endif

                </h5>
            </div>

            @if($donorSession)
                <div class="actions">

                    @if(\App\Models\ContactFund::isViewable($fund->fund_id))
                        <a href="{{ route('fund', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">{{ $custom->text->FUND_OVERVIEW }}</a>
                    @endif

                    @if(\App\Models\ClientInfo::isJCF())
                        <a href="{{ route('grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme"
                           data-toggle="tooltip" title="Disbursements made out of your fund">Grant History</a>
                    @else
                        <a href="{{ route('grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">Grant History</a>
                    @endif

                    @if(\App\Models\ClientInfo::isJCF())
                        <a href="{{ route('gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme"
                           data-toggle="tooltip" title="Contributions made into your fund">{{ $custom->text->GIFT_HISTORY }}</a>
                    @else
                        <a href="{{ route('gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">{{ $custom->text->GIFT_HISTORY }}</a>
                    @endif

                    <a href="{{ route('grant-create', ['fund_id' => $fund->fund_id]) }}" class="btn btn-sm btn-theme">{{ $custom->text->MAKE_A_GRANT }}</a>

                </div>
            @else
                <div class="actions agency">

                    @if(\App\Models\ContactFund::isViewable($fund->fund_id))
                        <a href="{{ route('fund', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">{{ $custom->text->FUND_OVERVIEW }}</a>
                    @endif

                    @if(\App\Models\ClientInfo::isJCF())
                        <a href="{{ route('grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme"
                           data-toggle="tooltip" title="Disbursements made out of your fund">Disbursement History</a>
                    @else
                        <a href="{{ route('grant-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">Disbursement History</a>
                    @endif

                    @if(\App\Models\ClientInfo::isJCF())
                        <a href="{{ route('gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme"
                           data-toggle="tooltip" title="Contributions made into your fund">Contribution History</a>
                    @else
                        <a href="{{ route('gift-history', [$fund->fund_id]) }}" class="btn btn-sm btn-theme">Contribution History</a>
                    @endif

                    @if(\App\Models\GhComposition::compositionExists($fund->fund_id))
                        <a href="{{ route('agency-fund-performance', ['fund_id' => $fund->fund_id]) }}" class="btn btn-sm btn-theme">Fund Performance</a>
                    @endif

                </div>
            @endif

        </div>
    </div>
</div>

@if(\App\Models\ClientInfo::isJCF())
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });
        });
    </script>
@endif
