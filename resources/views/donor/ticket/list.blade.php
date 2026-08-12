<?php
$donorSession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($myTicket as $i => $tickets)  
    @include("donor.ticket.list-item", ['tickets' => $tickets, 'total' => count($myTicket), 'donorSession' => $donorSession])
@empty
    @include("utils.data-not-found", [])
@endforelse
