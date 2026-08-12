<div class="col-12">
    <div class="fund-pool pool-default">
        <a class="pool-kv js_toggle_pool_values" 
        title="The Stephen Family Fund">
            <span class="name">
            {{ $recom['org_name'] }}</span>
            <span class="amount"> {{ $recom['amount'] }} </span>
        </a>
        <small><i><b>Recommend By:</b> {{ $recom['contact_name'] }}</i></small>,
        <small><i><b>Fund:</b> {{ $recom['fund_name'] }}</i></small>,
        <small><i><b>Status:</b> 
        @if($recom['status'] == 'N')
            Approval Pending
        @else
            Approved on {{ $recom['approved_date'] }}
        @endif
        </i></small>,
        <small><i><b>Created On:</b> {{ $recom['date_submitted'] }}</i></small>

        @if ($recom['ticket'] != '')
            <a target="_blank" style="float: right;color:#00758f" 
                href="{{ route('agency-service-ticket-view',['ticket_id' => $recom['ticket']]) }}">
                <small><i><b>View Ticket</b></i></small>
            </a>
        @else
            <a target="_blank" style="float: right;color:red;" 
                href="{{ route('agency-service-ticket-create', ['recommendation_id' => $recom['fund_recommendation_id']]) }}">
                <small><i><b>Create Ticket</b></i></small>
            </a>
        @endif
    </div>   
</div>