<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Notifications";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-12 col-r-15">
                    <h3 class="page-subtitle uppercase mt-2">
                    </h3>
                    @include('donor.notification.notification-list-loader')
                    <br>
                </div>  
            </div>
        </div>
    </div>
    <script>
    getAllNotificationList();
    function getAllNotificationList() {

        jsNotificationListLoader.init('/m/notifications-ajax');
        jsNotificationListLoader.runLoadData();
    }
    </script>
@endsection
