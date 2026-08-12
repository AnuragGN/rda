
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/ma/plugins/daterangepicker/moment.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>

<script>

// functions for tasks
function getTaskList(type) 
{
    var task_type = $("#task_type").val();
    $.ajax({
        type        : 'GET',
        url         : "{{ route('agency-task-list') }}",
        data        : {'task_type':task_type,"type":type},
        dataType    : 'json',
        success     : function(data)
        {  
            var currentDate = new Date();
            var html = `<tr>
                        <th style="text-align: left !important;">Task Type</th>
                        <th style="text-align: left !important;">Fund Name</th>
                        <th style="text-align: left !important;">Subject</th>
                        <th style="text-align: left !important;">End On</th>
                        <th style="text-align: left !important;">Status</th>
                        <th style="text-align: left !important;">Action</th>
                    </tr>`;
            if(data.length >0)
            {
                for (var i in data) 
                {
                    var color    = '';
                    var fund_id  = data[i].fund_id;
                    var task_id  = data[i].task_id;

                    var currentDate = new Date();
                    if(data[i].status == 'Pending')
                    {
                        var specificDate = new Date(data[i].end_date);
                        if(specificDate < currentDate)
                        {
                            data[i].status = 'Over Draft';
                            color          = 'red';
                        }
                    }
                    var end_date = formatDate(data[i].end_date);
                    html += `<tr>
                        <td style="text-align: left !important;">`+data[i].task_type+`</td>
                        <td style="text-align: left !important;">`+data[i].fund_name+`</td>
                        <td style="text-align: left !important;">`+data[i].subject+`</td>
                        <td style="text-align: left !important;color:`+color+`">`+end_date+`</td>
                        <td style="text-align: left !important;color:`+color+`">`+data[i].status+`</td>
                        <td style="text-align: left !important;"><a style="color:#fff;" class="btn btn-accent btn-sm" onclick="getTaskDetail(`+task_id+`,'`+type+`');">View</a>&nbsp;<a style="color:#fff;" class="btn btn-danger btn-sm" onclick="deleteTask(`+task_id+`,'`+type+`');">Delete</a></td>
                    </tr>`;
                }
            }
            else
            {
                html += `<tr>
                        <td><i class="fas fa-exclamation-triangle"></i> No data available.</td>
                    </tr>`;
            }
            $("#"+type+"TaskDiv").html(html);
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

// Function to get and populate task details in the modal
function getTaskDetail(taskId,type) {

    $.ajax({
        type: 'GET',
        url: "{{ route('agency-task-detail') }}",
        data: { 'taskId': taskId },
        success: function (data) {
            populateTaskDetail(data,type);
        }
    });
}

// Function to populate the modal with task details
function populateTaskDetail(taskData,type) {

    $("#task_id_href").attr("href", "/m/agency/services/edit-task/" + taskData.task_id);
    $("#editTaskBtn").show();

    // Populate task details
    $("#TaskDetailDiv").html(`
        <div style="display: flex; flex-direction: column; margin-left: 20px;">
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Fund Name:</label> <span>${taskData.fund_name}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Task Type:</label> <span>${taskData.task_type}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Subject:</label> <span>${taskData.subject}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Description:</label> <span>${taskData.description}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Start Date:</label> <span>${taskData.start_date}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">End Date:</label> <span>${taskData.end_date}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Created On:</label> <span>${taskData.created_at}</span></div>
            <div style="display: flex; margin-bottom: 10px;"><label style="width: 100px;">Priority:</label> <span>${taskData.task_priority}</span></div>
            <div style="display: flex;"><label style="width: 100px;">Status:</label> <span style="color:'.$color.';">${taskData.status}</span></div>
            <!-- Add other task details here -->
            <input type="hidden" id="hyd_type" value="${type}">
            <input type="hidden" id="hyd_task_id" value="${taskData.task_id}">
            <input type="hidden" id="hyd_status_id" value="${taskData.status}">
        </div>
    `);

    // Check task status and show/hide Close Task button
    if (taskData.status === 'Close') {
        $("#updateBtnHide").hide();
    }

    // Show the modal
    $("#exampleModal").modal('show');
}

$(document).ready(function() {
    setTimeout(function() {
        $("#success-alert").fadeOut('slow');
    }, 3000); // 3000 milliseconds (3 seconds)
});

//ticket close function used in service-tickets/view.blade.php
function closeTicket(ticket_id) {
    
    var message = "Are you sure you want to close this ticket?";
        $.confirm({
        columnClass: 'medium',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function()
                {
                    // no action
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-accent',  
                keys: ['enter', 'shift'],
                action: function()
                {
                   $.ajax({
                        type        : 'GET',
                        url         : "{{ route('agency-ticket-update') }}",
                        data        : {'ticket_id':ticket_id},
                        success     : function(data)
                        {  
                            location.reload();
                        }
                    });
                }
            }
        }
    });
}

function addComment() {
    
    $("#error_msg").html('');
    var comment  = $.trim($("#comment_text").val());
    if(comment == ''){
        $("#error_msg").html('This field is required!'); return false;
    }

    var formData = new FormData($('#uploadForm')[0]);

    $.ajax({
        type        : 'post',
        url         : "{{ route('service-ticket-add-comment') }}",
        data        : formData,
        contentType: false,
        processData: false,
        success     : function(data)
        {  
            $('#uploadForm')[0].reset();
            getComment();
            $("#view_chat_record_div").html(data.success);

            setTimeout(function() {
                $("#view_chat_record_div").html('');
            }, 15000);
        }
    });
}

function getDonorEmails() {

    var selectedValue = $("#id_fund_id").val();

    $.ajax({
        type: 'GET',
        url: "{{ route('agency-donor-email') }}",  
        data: { 'donor_id': selectedValue },
        success: function (response) {
            // Handle the JSON response and construct HTML on the client side
            var donorEmails = response.donorEmails;
            var  html = '<option value="0">Select Assign To</option>';

            if (donorEmails.length > 0) {
                donorEmails.forEach(function (email) {
                    

                    html += '<option value="' + email.contact_id +'">' + email.first_name +" "+ email.last_name +'</option>';
                });
            }
            $("#donor_id").html(html);
        }
    });
}

function deleteTicket(ticket_id,type) {  

    var message = "Are you sure you want to archive this ticket?";
        $.confirm({
        columnClass: 'medium',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function()
                {
                    // no action
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-accent',
                keys: ['enter', 'shift'],
                action: function()
                {
                    $.ajax({
                        type        : 'GET',
                        url         : "{{ route('agency-delete-ticket') }}",
                        data        : {'ticket_id':ticket_id},
                        success: function(data) {  
                            if (type != 'dashboard') {

                                $("#successMessage")
                                    .html(data.message)
                                    .fadeIn()
                                    .delay(1500)
                                    .fadeOut();

                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            }
                        }
                    });
                }
            }
        }
    });
}

function getFundsByCharity() {
    var selectedCharityId = $("#id_charity_id").val();

    $.ajax({
        type: 'GET',
        url: "{{ route('agency-service-ticket-create') }}", 
        data: { 'charity_id': selectedCharityId },
        success: function (response) {
            // Handle the JSON response and construct HTML on the client side
            var funds = response.funds;
            var html = '<option value="0">Select Fund</option>';

            if (funds.length > 0) {
                funds.forEach(function (fund) {
                    html += '<option value="' + fund.fund_id + '">' + fund.name + '</option>';
                });
            }
            $("#id_fund_id").html(html);
        }
    });
}

function ticketHistory(ticket_id,ticket_name){

    $.ajax({
        type: 'get',
        url: "{{ route('service-ticket-get-comment') }}",
        data: {
            "ticket_id": ticket_id,
            "type": '1'
        },
        dataType: 'json',
        success: function(data) {

            var currentDate = new Date();
            var ticket_history = ``;
            if (data.length > 0) {

                for (var i in data) {

                    var className = (i % 2 === 0) ? 'timeline_left' : 'timeline_right';
                    var created_at = moment(data[i].created_at).format('DD-MM-YYYY HH:mm');
                    var media_arr = data[i].media;

                    if (data[i].private == 1) {
                        var fafa_icon = '<i title="Private message" class="fa fa-user-secret"></i>';
                        var bg_color = '#f1f0d6';

                        if(className == 'timeline_left'){

                            className = 'timeline_left_private'
                        }
                        if(className == 'timeline_right'){

                            className = 'timeline_right_private'
                        }
                    } else {
                        var fafa_icon = '<i title="Ticket Log" class="nav-icon fas fa-file"></i>';
                        var bg_color = 'ebf1fc';
                    }  
                    ticket_history += `<div class="container_timeline ${className}">
                        <div class="timeline_content" style="background-color:${bg_color}">`;

                            ticket_history += `<p>${fafa_icon} ${data[i].comment}</p>`;

                            if (media_arr.length > 0) {

                                for (var j in media_arr) {

                                    ticket_history += `<small><i class="fa fa-download" aria-hidden="true"></i>&nbsp<a title="${media_arr[j].name}" href="{{ asset('uploads/tickets/${media_arr[j].file_name}') }}" download>${media_arr[j].name}</a></small><br>`;
                                }
                            }
                            ticket_history += `<p>
                            <small><i class="nav-icon fas fa-user"></i> ${data[i].comment_added_by}</small> 
                            <small style="float: right;"><i class="fa fa-clock-o" style="font-size:48px;color:red;"></i> ${data[i].created_at_format}</small>
                            </p>
                        </div>
                        </div>
                    </div>`;
                }
            } else {
                ticket_history += `<p>There are no history.</p>`;
            }
            $("#ticketHistoryModal").modal('show');
            $("#ticket_history_div").html(ticket_history);
            $("#hyd_ticket_name").html(ticket_name);
        }
    });
}

/*
** Ticket Chart Generation
*/

function generateTicketChart(type, ticketData = []) 
{
    // Get selected chart type if empty
    if (!type) {
        type = $("#chart_id").val();
    }

    // Save chart preference (same as before)
    updateChartPreference(type);
    

    // Use global data if not passed
    if (!ticketData || ticketData.length === 0) {
        ticketData = window.currentTicketData || [];
    }

    if (ticketData.length === 0) {
        console.warn('No ticket data available');
        return;
    }

    /* ---------------------------------------------------------
    Prepare Chart Data
    ---------------------------------------------------------- */

    const labels = [];
    const values = [];

    ticketData.forEach(function (ticket) {
        labels.push(ticket.status_name);
        values.push(Number(ticket.total));
    });

    const ctx = document.getElementById('chartContainer').getContext('2d');

    // Destroy old chart before re-render
    if (ticketChart) {
        ticketChart.destroy();
    }

    ticketChart = new Chart(ctx, {
        type: type, // pie | doughnut
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#28a745', // green
                    '#ffc107', // yellow
                    '#dc3545', // red
                    '#17a2b8', // blue
                    '#6f42c1'  // purple
                ]
            }]
        },
        options: {
            responsive: true,
            cutout: type === 'doughnut' ? '60%' : '0%',
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.label + ': ' + context.parsed;
                        }
                    }
                }
            }
        }
    });
}

/*
** Recommendation Chart Generation
*/

function generateRecommendationChart(type, RecommData = []) 
{
    if (!type) 
    {
        type = $("#chart_id").val();
    }

    if (!RecommData || RecommData.length === 0) 
    {
        RecommData = window.currentRecommendationData || [];
    }

    if (RecommData.length === 0) {
        console.warn('No graph data available');
        return;
    }

    const labels = [];
    const values = [];

    RecommData.forEach(function (item) {
        labels.push(item.name);
        values.push(Number(item.amount));
    });

    const ctx = document.getElementById('chartContainer').getContext('2d');

    if (recommendationChart) {
        recommendationChart.destroy();
    }

    recommendationChart = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#17a2b8',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            cutout: type === 'doughnut' ? '60%' : '0%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.label + ': ' + context.parsed;
                        }
                    }
                }
            }
        }
    });
}

function updateChartPreference(type) 
{
   $.ajax({
        url: "{{ route('save.chart.preference') }}",
        method: 'POST',
        data: {
            chart_type: type,
            _token: '{{ csrf_token() }}'
        }
    });
}

/*
** Initialize Summernote
*/

$(document).ready(function() {
    $('.summernote').summernote({
        height: 150,
        placeholder: 'Type Description...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'codeview']]
        ],
    });
});

</script>

<div class="modal fade" id="ticketHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span href="" class="closeBtn" id="close" aria-hidden="true" data-dismiss='modal' title="Close" data-toggle="tooltip"><i class="fa fa-times"></i></span>
                <div class="col-lg-12">
                    <div class="manage-deadline">
                        <h3>Ticket History [<small><span id="hyd_ticket_name"></span></small>]</h3>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div id="one">
                        <div class="col-lg-12 order_logs timeline" id="ticket_history_div" style="height: 400px; overflow: auto;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right" style="display: block;"></div>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close" 
                style="float: right;">Close</button>
            </div>
        </div>
    </div>
</div>
