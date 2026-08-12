@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Dashboard', 'hcXlWidth' => 12])
    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-12 col-r-15 fund-advisors">
                        <div class="row">
                            <!-- Fund Balance -->
                            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                                <div class="chart-box mt-2">
                                    <div class="title">Top 5 Fund Balance</div>
                                    <div class="row">
                                        @foreach($funds as $fundkey => $fund)
                                        <div class="col-12">
                                            <div class="fund-pool pool-default">
                                                <a class="pool-kv js_toggle_pool_values" 
                                                title="The Stephen Family Fund">
                                                    <span class="name">
                                                    {{ $fund['name'] }}</span>
                                                    <span class="amount"> {{ $fund['balance_format'] }}</span>
                                                </a>
                                                <a href="{{ route('agency-fund', [$fund['fund_id']]) }}"><small><u><i>Fund Overview</i></u></small></a>
                                                &nbsp;
                                                <a href="{{ route('agency-grant-history', [$fund['fund_id']]) }}"><small><u><i>Disbursement History</i></u></small></a>
                                                &nbsp;
                                                <a href="{{ route('agency-gift-history', [$fund['fund_id']]) }}"><small><u><i>Contribution History</i></u></small></a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <div class="fund-pool pool-default">
                                                <a href="{{ route('agency-funds') }}" class="pool-kv">
                                                    <span class="name">Explore More</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->

                            <!-- Open Tickets -->
                            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                                <div class="chart-box mt-2">
                                    <div class="title">Latest 5 Open Tickets</div>
                                    <div class="row">
                                        @if(count($openTickets) > 0)  
                                            @foreach($openTickets as $ticketKey => $ticket)
                                            <div class="col-12">
                                                <div class="fund-pool pool-default">
                                                    <a class="pool-kv js_toggle_pool_values" 
                                                    title="The Stephen Family Fund">
                                                        <span class="name">{{ $ticket['title'] }}</span>
                                                        <span class="amount">
                                                            <i onclick="viewTicket({{ $ticket['id'] }});" title="View Ticket" class="fa fa-eye" aria-hidden="true" 
                                                            style="color:#00758f;cursor:pointer;"></i> | 

                                                            <i onclick="editTicket({{ $ticket['id'] }},'dashboard');" title="Edit Ticket" class="fa fa-edit" aria-hidden="true" 
                                                            style="color:#00758f;cursor:pointer;"></i> | 

                                                            <i onclick="deleteTicket({{ $ticket['id'] }},'dashboard');" title="Archive Ticket" class="fa fa-archive" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i>

                                                        </span>  
                                                    </a>
                                                    <small><i><b>Ticket Type:</b> {{ config('dropdown.category.' . $ticket['category']) }}</i></small>,
                                                    <small><i><b>Priority:</b> {{ config('dropdown.priority.' . $ticket['priority']) }}</i></small>,
                                                    <small><i><b>Created At:</b> {{ \App\Helpers\GnUtils::customDate($ticket['created_at']) }}</i></small>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <span>Open ticket is not found!</span>
                                        </div>
                                        @endif
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <div class="fund-pool pool-default">
                                                <a href="{{ route('agency-ticket') }}" class="pool-kv">
                                                    <span class="name">Explore More</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Grants -->

                            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                                <div class="chart-box mt-2">
                                    <div class="title">
                                        Top 5 Grants<small>[Last 30 Days]</small>
                                    </div>
                                    <div class="row">
                                        @foreach($overAllGrant as $fundkey => $fundval)

                                        <div class="col-12">
                                            <div class="fund-pool pool-default">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                                title="Click to Expand / Collapse" data-target-id="fundGrantDiv-{{ $fundval['fund_id'] }}">
                                                    <span class="name">
                                                    <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                    <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                    {{ $fundval['fund_name'] }}</span>
                                                    <span class="amount"> {{ $fundval['total_fund_grant_format'] }}</span>
                                                </a>
                                                <div class="pool-values" id="fundGrantDiv-{{ $fundval['fund_id'] }}" style="display: none;">

                                                    @foreach($fundval['organization_data'] as $orgkey => $orgval)
                
                                                    <div class="">
                                                        <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" title="Click to Expand / Collapse" data-target-id="orgGrantDiv-{{ $fundval['fund_id'] }}-{{ $orgval['organization_id'] }}">
                                                            <span class="name" style="font-weight: 450;">
                                                                <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                                <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                                {{ $orgval['organization_name'] }}
                                                            </span>
                                                            <span class="amount" style="font-weight: 400;"> {{ $orgval['total_org_grant'] }}</span>
                                                        </a>
                                                        <div class="pool-values" id="orgGrantDiv-{{ $fundval['fund_id'] }}-{{ $orgval['organization_id'] }}" style="display: none;">

                                                            @foreach($orgval['donor_data'] as $donorkey => $donorval)
                                                            <div class="fund-kv">
                                                                <span><small><a target="_blnak" href="{{ route('agency-client-detail', ['id' => $donorval['contact_id']]) }}">{{ $donorval['contact_name'] }}</a></small></span>
                                                                <span><small>{{ $donorval['total_donor_grant'] }}</small></span>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <div class="fund-pool pool-default">
                                                <a href="{{ route('agency-grants') }}" class="pool-kv">
                                                    <span class="name">Explore More</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->

                            <!-- Gift -->
                            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                                <div class="chart-box mt-2">
                                    <div class="title">
                                    Top 5 Gifts<small>[Last 30 Days]</small>
                                    </div>
                                    <div class="row">  

                                        @foreach($overAllGift as $fundkey => $fundval)
                                        <div class="col-12">
                                            <div class="fund-pool pool-default">
                                                <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values" 
                                                title="Click to Expand / Collapse" data-target-id="fundGiftbutionDiv-{{ $fundval['fund_id'] }}">
                                                    <span class="name">
                                                    <small id="id_pool_open" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                    <small id="id_pool_closed" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                    {{ $fundval['fund_name'] }}</span>
                                                    <span class="amount"> {{ $fundval['total_fund_grant_format'] }}</span>
                                                </a>
                                                
                                                <div class="pool-values" id="fundGiftbutionDiv-{{ $fundval['fund_id'] }}" style="display: none;">
                                                    @foreach($fundval['donor_data'] as $donorkey => $donorval)
                                                    <div class="fund-kv">
                                                        <span>{{ $donorval['contact_name'] }}</span>
                                                        <span>{{ $donorval['total_donor_grant_format'] }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <div class="fund-pool pool-default">
                                                <a href="{{ route('agency-gifts') }}" class="pool-kv">
                                                    <span class="name">Explore More</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->


                            <!-- Recommendation -->
                            <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                                <div class="chart-box mt-2">
                                    <div class="title">
                                    Top 5 Recommendations<small>[Last 30 Days]</small>
                                    </div>
                                    <div class="row">
                                        @foreach($recommendation as $key => $val)
                                        <div class="col-12">
                                            <div class="fund-pool pool-default">
                                                <a class="pool-kv js_toggle_pool_values" 
                                                title="The Stephen Family Fund">
                                                    <span class="name">
                                                    {{ $val['org_name'] }}</span>
                                                    <span class="amount"> {{ $val['amount'] }} </span>
                                                </a>
                                                <small><i><b>Recommend By:</b> {{ $val['contact_name'] }}</i></small>,
                                                <small><i><b>Fund:</b> {{ $val['fund_name'] }}</i></small>,
                                                <small><i><b>Status:</b> 
                                                @if($val['status'] == 'N')
                                                    Approval Pending
                                                @else
                                                    Approved on {{ $val['approved_date'] }}
                                                @endif
                                                </i></small>,<br>
                                                <small><i><b>Created On:</b> {{ $val['date_submitted'] }}</i></small>

                                                @if ($val['ticket'] != '')
                                                    <a target="_blank" style="float: right;color:#00758f" 
                                                        href="{{ route('agency-service-ticket-view',['ticket_id' => $val['ticket']]) }}">
                                                        <small><i><b>View Ticket</b></i></small>
                                                    </a>
                                                @else
                                                    <a target="_blank" style="float: right;color:red;" 
                                                        href="{{ route('agency-service-ticket-create', ['recommendation_id' => $val['fund_recommendation_id']]) }}">
                                                        <small><i><b>Create Ticket</b></i></small>
                                                    </a>
                                                @endif
                                            </div>   
                                        </div>
                                        @endforeach
                                        <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
                                            <div class="fund-pool pool-default">
                                                <a href="{{route('agency-recommendation')}}" class="pool-kv">
                                                    <span class="name">Explore More</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@include('agency.agency-advisor.common-script') 
<script>
function viewTicket(ticket_id){

    window.location.href = 'ticket/view/'+ticket_id;
}
function editTicket(ticket_id){

    window.location.href = 'dashboard-ticket/edit/'+ticket_id;
}

</script>
@endsection

