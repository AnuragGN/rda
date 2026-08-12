
<?php
$assigned_by = $tickets->secondary_assigned_by;
$assignee = App\Models\Contact::find($assigned_by);

?>
{{--@if ($total == 1)--}}

<tr>
    <td style="text-align: left !important;">{{ $tickets->id }}</td>
    <td style="text-align: left !important;width:100px;">
        <a title="View Ticket" href="/m/support-staff/ticket/view/{{ $tickets->id }}">
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
        <small><b>Work Status :</b> {{ config('dropdown.support_staff_status')[$tickets->secondary_status] }} </small><br>
        <small><b>Ticket Priority :</b> {{ config('dropdown.priority')[$tickets->priority] }} </small>
    </td>
    <td>
        <input type="hidden" class="ticket-class" value="{{ $tickets->secondary_status }}">
        <a title="View Ticket" href="/m/support-staff/ticket/view/{{ $tickets->id }}" style="color:#0055a3;cursor:pointer;"><small><b><i><i class="fa fa-eye" aria-hidden="true" style="color:#00758f;"></i> View</i></b></small>
        </a>
        <br><a onclick="ticketHistory({{ $tickets->id }},'{{ $tickets->title }}');" style="color:#0055a3;cursor:pointer;"><small><b><i> Ticket History</i></b></small></a>
    </td>
</tr>
