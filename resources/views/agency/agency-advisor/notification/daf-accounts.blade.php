<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Notifications";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-12 col-r-15">
                    <div class="text-right">
                        <a href="{{route('agency-send-manual-notification')}}"
                           class="btn btn-accent btn-sm mr-2 ml-2">
                            Send Notification</a>
                    </div>
                    <h3 class="page-subtitle uppercase mt-2">
                    </h3>
                    @include('agency.agency-advisor.notification.notification-list-loader')
                    <br>
                </div>  
            </div>
        </div>
    </div>
    <script>
    getAllNotificationList();
    function getAllNotificationList() {

        jsNotificationListLoader.init('/m/agency/notifications-ajax');
        jsNotificationListLoader.runLoadData();
    }
    </script>
@endsection
