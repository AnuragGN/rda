{{-- @foreach($recommendation as $key => $val)
    <div class="col-12">
        <div class="fund-pool pool-default">
            <a class="pool-kv js_toggle_pool_values">
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
            </i></small>,
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
@endforeach --}}


@if(count($recommendation) > 0)
    @foreach($recommendation as $key => $val)
    <div class="col-12">
        <div class="fund-pool pool-default">
            <a class="pool-kv js_toggle_pool_values">
                <span class="name">{{ $val['org_name'] }}</span>
                <span class="amount">{{ $val['amount'] }}</span>
            </a>
            
            <small><i><b>Recommend By:</b> {{ $val['contact_name'] }}</i></small>,
            <small><i><b>Fund:</b> {{ $val['fund_name'] }}</i></small>,
            <small><i><b>Status:</b> 
            @if($val['status'] == 'N')
                Approval Pending
            @else
                Approved on {{ $val['approved_date'] }}
            @endif
            </i></small>,
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
@else
    <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
        <span>No pending recommendations found for the selected sponsor.</span>
    </div>
@endif

