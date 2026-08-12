<?php
$assigned_id = $tickets->assigned_to;

$secAssignee = App\Models\TicketAssignee::getAssignee($tickets->id);

if($secAssignee){
    $assigned_id = $secAssignee->assigned_id;
}
$assignee = App\Models\Contact::find($assigned_id);
?>
{{--@if ($total == 1)--}}

<tr>
    <td style="text-align: left !important;">{{ $tickets->id }}</td>
    <td style="text-align: left !important;width:100px;">
        <a title="View Ticket" href="/m/agency/ticket/view/{{ $tickets->id }}">
            <small>{{ $tickets->title }}</small>
        </a>
    </td>
    <td style="text-align: left !important;"><small>{{ @$assignee->first_name }} {{ @$assignee->last_name }}</small>
    </td>
    <td style="text-align: left !important;">
        <small>{{ \App\Helpers\GnUtils::customDate($tickets->created_at) }}</small>
    </td>
    <td style="text-align: left !important;">
        <small><b>Ticket Type :</b> {{ config('dropdown.category')[$tickets->category] }} </small><br>
        <small><b>Ticket Status :</b> {{ config('dropdown.status')[$tickets->status] }} </small><br>
        <small><b>Ticket Priority :</b> {{ config('dropdown.priority')[$tickets->priority] }} </small>
    </td>
    <td>
        <input type="hidden" class="ticket-class" value="{{ $tickets->status }}">
        <small><a title="View Ticket" href="/m/ticket/view/{{ $tickets->id }}">
            <i class="fa fa-eye" aria-hidden="true" style="color:#00758f;"></i>
        </a></small> | 

        <small><a title="Edit Ticket" href="/m/ticket/edit/{{ $tickets->id }}">
            <i class="fa fa-edit" aria-hidden="true" style="color:#00758f;"></i>
        </a></small> | 

        <small><a title="Archive Ticket" onclick="deleteTicket({{ $tickets->id }},'service-page');">
            <i class="fa fa-archive" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i>
        </a></small>
        <!-- <br><small><a onclick="ticketHistory({{ $tickets->id }},'{{ $tickets->title }}');" style="color:#0055a3;cursor:pointer;"><small><b><i> Ticket History </i></b></small></a><small> -->
    </td>
</tr>    

<script>
    
function deleteTicket(ticketId)
{
    var message = "Are you sure you want to archive this ticket?";
        $.confirm({
        columnClass: 'medium',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function()
                {
                    // no action
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-accent',
                keys: ['enter', 'shift'],
                action: function()
                {
                    $.ajax({
                        type        : 'GET',
                        url         : "/m/ticket/delete",
                        data        : {'ticketId':ticketId},
                        success     : function(data)
                        {  
                            location.reload();
                        }
                    });
                }
            }
        }
    });
}

</script>


