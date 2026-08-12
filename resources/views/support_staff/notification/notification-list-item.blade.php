
{{--@if ($total == 1)--}}
<?php

$read_user_ids = $notification->read_user_ids;
$read_user_ids_arr = explode(',', $read_user_ids);

$readAtDate = '';
if(in_array($contact_id, $read_user_ids_arr))
{
    $read_at_arr = json_decode($notification->read_at);
    
    foreach ($read_at_arr as $item) {
        if ($item->contact_id == $contact_id) {
            $readAtDate = $item->read_at;
            break;
        }
    }
}
?>
<tr>
    <td style="text-align: left !important;">{{ $notification->notification }}</td>
    <td style="text-align: left !important;">{{ $notification->sender_first_name }} {{ $notification->sender_last_name }}</td>
    
    <td style="text-align: left !important;">{{ \App\Helpers\GnUtils::customDate($notification->created_at) }}</td>

    <td style="text-align: left !important;">

        @if($contact_id == $notification->from)
            --
        @else
            @if(in_array($contact_id, $read_user_ids_arr))
                <small>Acknowledged  <br>{{ \Carbon\Carbon::parse($readAtDate)->format('m-d-Y H:i') }}</small>
           @else
                <a title="Mark As Read" style="cursor: pointer;color:red;" 
                onclick="markAsRead({{ $notification->id }},'',1);"><small style="font-weight: bold;">Acknowledge</small></a>
           @endif
        @endif
    </td>
</tr>    


