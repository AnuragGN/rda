<script>
getfund();
function getfund()
{
    var fund_id = $("#fund_id").val();
    jsAdvisorCartListLoader.init('/m/agency/cart-list');
    jsAdvisorCartListLoader.getfundId(fund_id);
    jsAdvisorCartListLoader.runLoadData();
}

function get_notification_popup(cart_id){

    $('#notification').css('border-color', '');
    $("#hyd_cart_id").val(cart_id);
    $("#exampleModal").modal('show');
}

function send_notification(){

    $('#notification').css('border-color', '');
    var cart_id      = $("#hyd_cart_id").val();
    var notification = $.trim($("#notification").val());
    if(notification == '')
    {
        $("#notification").css('border-color', 'red');
    }
    $.ajax({
        type        : 'GET',
        url         : "/m/agency/sent-notification",
        data        : {'cart_id':cart_id,"notification":notification},
        success     : function(data)
        {  
            $("#success_msg").html(data);
            $("#notification").val('');
            setTimeout(function() {
                $("#success_msg").html("");
                $("#exampleModal").modal('hide');
            }, 2000);
        }
    });
}

function notification_logs(cart_id){

    $.ajax({
        type        : 'GET',
        url         : "/m/agency/notification-logs",
        data        : {'cart_id':cart_id},
        success     : function(data)
        {  
            var html = '';
            if(data.length > 0) 
            {
                $.each(data, function(i, result) 
                {
                    var isRead = 'Unread';
                    if(result.is_read == 'Y'){
                        isRead = 'Read';
                    }
                    var formattedDate = formatDate(result.notification_on);
                    html += `<tr>
                                <td style="text-align: left !important;">`+result.notification+`</td>
                                <td style="text-align: left !important;">`+formattedDate+`</td>
                                <td style="text-align: left !important;">`+isRead+`</td>
                            </tr>`;
                });
            }
            else{
                html += `<tr>
                        <td colspan="3" style="text-align: center !important;"><i class="fas fa-exclamation-triangle"></i> No data available!</td>
                    </tr>`;
            }   

            $("#notification-logs-div").html(html);
            $("#exampleModalNotificationLogs").modal('show');
        }
    });
}

function formatDate(date) {
    var d = new Date(date);

    var year    = d.getFullYear();
    var month   = ("0" + (d.getMonth() + 1)).slice(-2);
    var day     = ("0" + d.getDate()).slice(-2);
    var hours   = ("0" + d.getHours()).slice(-2);
    var minutes = ("0" + d.getMinutes()).slice(-2);
    var seconds = ("0" + d.getSeconds()).slice(-2);

    var formattedDate = day + "-" + month + "-" + year;
    return formattedDate;
}
</script>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="body">
                        <div class="form-group row" style="margin-bottom: 0">
                            <label for="notes" class="col-sm-12 col-form-label text-left pr-0">Notification</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" rows="2" name="notification" cols="50" id="notification" maxlength="150"></textarea>
                                <span class="from-footer" id="error_msg">Max 150 Characters</span>
                                <input type="hidden" id="hyd_cart_id" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="from-footer" id="success_msg" style="color:green;"></span>
                <button id="updateBtnHide" type="button" class="btn btn-accent btn-sm" 
                onclick="send_notification();" id="submit_btn">Send Notification </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalNotificationLogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notification Logs  aasss</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="body">
                        <div class="form-group row" style="margin-bottom: 0">
                           <div class="col-xl-12" style="max-height: 500px;overflow-y: auto;">
                                <table class="table-pending-grants">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left !important;">Notification</th>
                                            <th style="text-align: left !important;">Sent On</th>
                                            <th style="text-align: left !important;">Notification Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notification-logs-div">
                                        
                                    </tbody>   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>