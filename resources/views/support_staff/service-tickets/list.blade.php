<?php
$supportStaffSession = \App\Helpers\GnUtils::isSupportStaffSession();
?>

@forelse($myTicket as $i => $tickets)  
    @include("support_staff.service-tickets.list-item", ['tickets' => $tickets, 'total' => count($myTicket), 'supportStaffSession' => $supportStaffSession])
@empty
    @include("utils.data-not-found", [])
@endforelse
