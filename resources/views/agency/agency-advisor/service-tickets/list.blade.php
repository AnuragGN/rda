<?php
$agencySession = \App\Helpers\GnUtils::isAgencySession();
?>

@forelse($myTicket as $i => $tickets)  
    @include("agency.agency-advisor.service-tickets.list-item", ['tickets' => $tickets, 'total' => count($myTicket), 'agencySession' => $agencySession])
@empty
    @include("utils.data-not-found", [])
@endforelse
