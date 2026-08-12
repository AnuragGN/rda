<?php
$pageTitle = \App\Models\ClientInfo::isHGA() ? "" : "Notifications";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-12 col-r-15">

                    <h3 class="page-subtitle uppercase mt-2">
                        <!-- {{ \App\Models\ClientInfo::isHGA() ? "Fund Overview" : "Funds" }} -->
                    </h3>
                    @include('donor.notification.notification-list-loader')
                    <br>
                </div>  
            </div>
        </div>
    </div>
    <script>
        $(function() {
            jsNotificationListLoader.init('/m/my-notification-ajax');
            jsNotificationListLoader.runLoadData();
        });

        function markAsRead(notification_id){  
            $.ajax({
                type        : 'GET',
                url         : "/notification-mark-as-read",
                data        : {"notification_id":notification_id},
                success     : function(data)
                {  
                    location.reload();
                }
            });
        }
    </script>
@endsection
