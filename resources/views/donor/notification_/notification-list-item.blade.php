
{{--@if ($total == 1)--}}

<tr>
    <td style="text-align: left !important;">{{ $notification->notification }}</td>
    <td style="text-align: left !important;">{{ $notification->sender_first_name }} {{ $notification->sender_last_name }}</td>
    <td style="text-align: left !important;">{{ \Carbon\Carbon::parse($notification->notification_on)->format('d-m-Y H:i') }}</td>
    <td style="text-align: left !important;">{{ $notification->is_read == "N" ? 'Unread' : 'Read'; }}</td>

    <td style="text-align: left !important;">
        @if($notification->is_read == "N")
        <a style="color:#fff;" class="btn btn-accent" onclick="markAsRead({{ $notification->id }});">Acknowledged</a>
       @else
        <!-- {{ \Carbon\Carbon::parse($notification->updated_at)->format('d-m-Y H:i') }} -->
       @endif
    </td>
    </tr>
</tr>    


