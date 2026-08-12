<?php
$agencySession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($notifications as $i => $notification)  
    @include("support_staff.notification.notification-list-item", ['notification' => $notification, 'total' => count($notifications), 'agencySession' => $agencySession])
@empty
    @include("utils.data-not-found", [])
@endforelse
