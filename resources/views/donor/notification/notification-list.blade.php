<?php
$donorsession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($notifications as $i => $notification)  
    @include("donor.notification.notification-list-item", ['notification' => $notification, 'total' => count($notifications), 'donorsession' => $donorsession])
@empty
    @include("utils.data-not-found", [])
@endforelse
