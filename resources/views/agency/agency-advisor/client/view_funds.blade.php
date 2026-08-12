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
<div class="row profile-view">
    <div class="col-md-9 fund-advisors">
        <div class="card gn-shadow">
            <div class="header">
                <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_funds_view">
                    <span class="open"><i class="fas fa-caret-down"></i></span>
                    <span class="closed"><i class="fas fa-caret-right"></i></span>
                    Funds
                </div>
            </div>
            <div class="body address" id="id_funds_view">
                <div class="col-md-12">
                    <div class="row">
                        @foreach($fundData as $key => $fund)
                        <div class="col-md-6">
                            <div class="gn-card card-fund-item gn-shadow">
                                <span class="advisor-name">{{ $fund['fund_name'] }}</span><br>
                                
                                <a href="{{ route('agency-fund', [$fund['fund_id']]) }}"><small><u><i>Fund Overview</i></u></small></a>
                                &nbsp;

                                <a href="{{ route('agency-grant-history', [$fund['fund_id']]) }}"><small><u><i>Disbursement History</i></u></small></a>
                                &nbsp;

                                <a href="{{ route('agency-gift-history', [$fund['fund_id']]) }}"><small><u><i>Contribution History</i></u></small></a>

                            </div>
                        </div>
                        @endforeach    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">

    </div>
</div>