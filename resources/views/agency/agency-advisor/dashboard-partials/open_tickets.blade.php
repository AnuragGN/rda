@if(count($openTickets) > 0)  
    @foreach($openTickets as $ticketKey => $ticket)
    <div style="border-bottom: 1px solid #c4c4c4;">
        <div class="fund-pool" style="box-shadow: none !important;">
            <a class="pool-kv">
                <span class="name" onclick="viewTicket({{ $ticket->id }});" style="cursor:pointer;">{{ $ticket->title }}</span>
                <span class="amount">
                    <i onclick="viewTicket({{ $ticket->id }});" title="View Ticket" class="fa fa-eye" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i> | 
                    <i onclick="deleteTicket({{ $ticket->id }},'dashboard');" title="Archive Ticket" class="fa fa-archive" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i> 
                </span>  
            </a>
            <small><i><b>Ticket Type:</b> {{ config('dropdown.category.' . $ticket->category) }}</i></small>,
            <small><i><b>Priority:</b> {{ config('dropdown.priority.' . $ticket->priority) }}</i></small>,
            <small><i><b>Created At:</b> {{ \App\Helpers\GnUtils::customDate($ticket->created_at) }}</i></small>
        </div>
    </div>
    @endforeach
    <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
    <a href="{{ route('agency-ticket') }}" class="btn btn-accent btn-sm mt-2">
        <span class="name">Show More</span>
    </a></div>
@else
<div class="col-12" style="display:flex;justify-content:center;align-items:center;">
    <span>No open tickets found!</span>
</div>
@endif
