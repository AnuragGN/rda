<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//js.pusher.com/3.1/pusher.min.js"></script>

<style>

/* Bell Notification CSS */

.notification-menu {
  position: absolute;
  background-color: #e5e5e5;
  border: #989898;
  padding: 10px;
  list-style: none;
  width: 305px;
  text-align: left;
  margin-left: -170px
}

#divNotificationList {
    max-height: 500px;
    overflow-y: auto;
}

/* Toast Custom Notification CSS */

.custom-toastr {
    background-color: #343a40 !important;
    color: white !important;
    border-radius: 10px !important;
    padding: 15px !important;
    opacity: 1 !important;
    width: 400px !important;
}
.custom-toastr-icon {
    background-image: none !important; 
}
.custom-toast-close {
    background-color: #fff;
    color: #000;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    margin-top: 10px;
    width: 100%;
}
</style>

<script type="text/javascript" >
    const pusher = new Pusher('ced6a7cb478bb516ce3a', {
        cluster: 'ap2',
        encrypted: true
    });

    const channel = pusher.subscribe('notifications');

    channel.bind('App\\Events\\NotificationEvent', function(data) {
        
        var originalSuccess = toastr.success;
        toastr.success = function(message, title, optionsOverride) {
            var customOptions = $.extend({}, toastr.options, optionsOverride, {
                iconClass: 'custom-toastr-icon',
                toastClass: 'custom-toastr'
            });
            return originalSuccess.call(this, message, title, customOptions);
        };

        // Set toastr options
        toastr.options = {
            "positionClass": "toast-bottom-right",  
            "closeButton": true,
            "progressBar": true,
            "timeOut": 0,
            "extendedTimeOut": 0,
            "onclick": null,
            "tapToDismiss": false,
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "target": "body",
        };

        setTimeout(function() {
           // get_bottom_notification();
           // get_unread_notification();
        }, 2000);
    });
    // setInterval(get_unread_notification, 3000);    
    function get_bottom_notification() {

        $.ajax({
            type        : 'GET',
            url         : "/m/bottom-notification",
            success     : function(response)
            {  
                if(response.notifications != null){

                    var result = response.notifications;
                    var user_role = response.user_role;

                    var target_type = result.target_type;
                    var target_id = result.target_id;
                    var notification = result.notification;
                    var from = result.sender_first_name+' '+result.sender_last_name;

                    var path = 'agency/';
                    if(user_role == 'support_staff'){
                        var path = 'support-staff/';
                    }
                    if(user_role == 'donor'){
                        var path = '';
                    }
                    var complete_path = "/m/"+path+"ticket/view/"+target_id;
                    if(target_type == 'notifications'){
                        complete_path = target_type;
                    }
                    $(".custom-toastr").css('display','');
                    var customHtml = `
                       <div>
                           <a href="${complete_path}"><strong>${notification}</strong>
                           <small><br>From: ${from}</small></a>
                           <button class="custom-toast-close" onclick="closeToast()">Close</button>
                       </div>`;
                    toastr.success(customHtml);
                }
            }
        });
    }

    function closeToast() {
       $(".custom-toastr").css('display','none');
    }

    function getNotilist() 
    {
       $("#notification_list").toggle();
    }

   // get_unread_notification();
    function get_unread_notification()
    {  
        $.ajax({
            type        : 'GET',
            url         : "/m/notification-list",
            success     : function(response)
            {  
                var notificationsWrapper   = $('.dropdown-notifications');
                var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
                var notificationsCountElem = notificationsToggle.find('i[data-count]');
                var notificationsCount     = parseInt(notificationsCountElem.data('count'));
                var notifications          = notificationsWrapper.find('ul.dropdown-menu');

                var newNotificationHtml = '';
                var notificationsCount  = 0;
                var endDate             = new Date();

                var user_role = response.user_role;

                var result = response.notifications;
                for (var i in result) 
                {
                    var notification_id = result[i].id;
                    var notification = result[i].notification;
                    var target_type = result[i].target_type;
                    var target_id = result[i].target_id;
                    var from_name = result[i].sender_first_name+' '+result[i].sender_last_name;

                    var startDate = new Date(result[i].created_at); 
                    var timeago   = calculateDateDifference(startDate, endDate);

                    var htmlCode = notificationlist(notification,timeago,notification_id,from_name,target_type,target_id,user_role);
                   
                    notificationsCount += 1;
                    newNotificationHtml += htmlCode;
                }

                var view_all = `<div class="dropdown-footer text-center">
                    <a href="{{ route('agency-notifications') }}"><strong>View All</strong></a>
                </div>`;

                if (user_role == 'support_staff') {

                    view_all = `<div class="dropdown-footer text-center">
                        <a href="{{ route('support-staff-notifications') }}"><strong>View All</strong></a>
                    </div>`;
                }

                if (user_role == 'donor') {

                    view_all = `<div class="dropdown-footer text-center">
                        <a href="{{ route('donor-notifications') }}"><strong>View All</strong></a>
                    </div>`;
                }

                newNotificationHtml += view_all;

                $("#divNotificationList").html(newNotificationHtml);
                $("#notification-count").html(notificationsCount);
                $("#total_notification").val(notificationsCount);
                notificationsWrapper.find('.notif-count').text(notificationsCount);
                notificationsWrapper.show();
            }
        });
    }

    function calculateDateDifference(startDate, endDate)   
    {
        var diff = endDate - startDate;
        if(diff <= 0)
        {
            return '5 sec(s) ago';
        }
        var seconds = Math.floor(diff / 1000);
        var minutes = Math.floor(diff / (1000 * 60));
        var hours = Math.floor(diff / (1000 * 60 * 60));
        var days = Math.floor(diff / (1000 * 60 * 60 * 24));
        
        var startDateObj = new Date(startDate);
        var endDateObj = new Date(endDate);

        var months = (endDateObj.getMonth() - startDateObj.getMonth()) + 
                        (12 * (endDateObj.getFullYear() - startDateObj.getFullYear()));
        
        var years = endDateObj.getFullYear() - startDateObj.getFullYear();

        if(years != '')
        {
            return years+ ' year(s) ago';
        }
        if(months != '')
        {
            return months+ ' month(s) ago';
        }
        if(days != '')
        {
            return days+ ' day(s) ago';
        }
        if(hours != '')
        {
            return hours+ ' hr(s) ago';
        }
        if(minutes != '')
        {
            return minutes+ ' min(s) ago';
        }
        if(seconds != '')
        {
            return seconds+ ' sec(s) ago';  
        }
    }
    function notificationlist(notification,timeago,notification_id,from_name,target_type,target_id,user_role)
    {
        var path = 'agency/';
        if(user_role == 'support_staff'){
            var path = 'support-staff/';
        }
        if(user_role == 'donor'){
            var path = '';
        }
        var complete_path = "/m/"+path+"ticket/view/"+target_id;
        if(target_type == 'notifications'){
            complete_path = target_type;
        }
        return html = `<div class="notification active">
            <div class="media">
                <div class="media-body">
                    <strong class="notification-title"><a href="${complete_path}">`+notification+`</a></strong>
                    <p class="notification-desc" style="font-size: 13px;">From: `+from_name+`</p>
                    <div class="notification-meta" style="margin-top: -10px;">
                        <small class="timestamp"><i class="fa fa-clock-o" aria-hidden="true"></i> `+timeago+`</small>
                        <small class="timestamp" style="float: right;color:red;">
                        <a style="cursor:pointer;" onclick="markAsRead(`+notification_id+`,'',0);">Acknowledge</a></small>
                    </div>
                </div>
            </div>
      </div><hr>`;
    }

    function markAsRead(notification_id,ticket_id,type)
    {  
        $.ajax({
            type        : 'GET',
            url         : "/m/notification-mark-as-read",
            data        : {"notification_id":notification_id,"ticket_id":ticket_id},
            success     : function(data)
            {  
                get_unread_notification();
                if(type == 1){
                    location.reload();
                }
            }
        });
    }
    </script>
