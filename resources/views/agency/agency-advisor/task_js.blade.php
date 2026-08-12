<script>
function getTaskList() 
{
    var taskType = $("#task_type").val() || 0;
    $.ajax({
        type: 'GET',
        url: "/m/agency/task-list",
        data: { 'seach_type': taskType },
        success: function (data) 
        {
            var taskDiv = $("#taskDiv"); 
            taskDiv.empty();
            if (data.dataset.length > 0) 
            {
                var table = $('<table class="table-pending-grants">');
                table.append('<tr><th style="text-align: left !important;">Task Type</th><th style="text-align: left !important;">Fund Name</th><th style="text-align: left !important;">Subject</th><th style="text-align: left !important;">End On</th><th style="text-align: left !important;">Status</th><th style="text-align: left !important;">Action</th></tr>');

                $.each(data.dataset, function (index, task) 
                {
                    var row = $('<tr>');
                    row.append('<td style="text-align: left !important;">' + task.task_type + '</td>');
                    row.append('<td style="text-align: left !important;">' + (task.fund_name ? task.fund_name: 'NA') + '</td>');
                    row.append('<td style="text-align: left !important;">' + task.subject + '</td>');
                    row.append('<td style="text-align: left !important;">' + (task.end_date ? task.end_date : 'N/A') + '</td>');
                    var currentDate = new Date();
                    var taskEndDate = new Date(task.end_date); // Convert the task end date to a JavaScript Date object

                    var statusColor = '';
                    
                    if (task.status === 'Pending') {
                        if (taskEndDate < currentDate) {
                            task.status = 'Over Draft';
                            statusColor = 'red';
                        }
                    }
                    
                    row.append('<td style="color: ' + statusColor + '">' + task.status + '</td>');
                    row.append('<td style="text-align: left !important;"><a href="#" class="btn btn-accent btn-sm" onclick="getTaskDetail(' + task.task_id + ');">View</a> <a href="#" class="btn btn-danger btn-sm" onclick="deleteTask(' + task.task_id + ');">Delete</a></td>');

                    table.append(row);
                });

                taskDiv.append(table);
            } 
            else 
            {
                // Display a message when there are no tasks
                taskDiv.html('<td><i class="fas fa-exclamation-triangle"></i> No data available.</td>');
            }
        },
        error: function () {
            // Handle errors if necessary
        }
    });
}
        // Call the function to initially populate the task list
        getTaskList();

        

function deleteTask(taskId)
{
    var message = "Are you sure you want to delete this task?";
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
                        url         : "/m/agency/delete-task",
                        data        : {'taskId':taskId},
                        success     : function(data)
                        {  
                            getTaskList();
                        }
                    });
                }
            }
        }
    });
}

// Function to get and populate task details in the modal
function getTaskDetail(taskId) {
    $.ajax({
        type: 'GET',
        url: "/m/agency/task-detail",
        data: { 'taskId': taskId },
        success: function (data) {
            populateTaskDetail(data);
        }
    });
}

// Function to populate the modal with task details
function populateTaskDetail(taskData) {
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
            <input type="hidden" id="hyd_task_id" value="${taskData.task_id}"><input type="hidden" id="hyd_status_id" value="${taskData.status}">
        </div>
    `);

    // Check task status and show/hide Close Task button
    if (taskData.status === 'Close') {
        $("#updateBtnHide").hide();
    }

    // Show the modal
    $("#exampleModal").modal('show');
}

function closeTask()
{
    var message = "Are you sure to you want to Close the Task ?";
        $.confirm({
        columnClass: 'medium',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function(){
                    // no action
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-accent',
                keys: ['enter', 'shift'],
                action: function(){
                    var taskId = $("#hyd_task_id").val();
                    $.ajax({
                        type        : 'GET',
                        url         : "/m/agency/task-update",
                        data        : {'taskId':taskId},
                        success     : function(data)
                        {  
                            $("#exampleModal").modal('hide');
                            getTaskList();
                        }
                    });
                }
            }
        }
    });
}
</script>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Task Detail</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="body" id="TaskDetailDiv">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-accent btn-sm" data-dismiss="modal" 
               style="margin-right: 228px;">Cancel</button>

                <a id="task_id_href" href="">
                    <button id="editTaskBtn" type="button" class="btn btn-accent btn-sm" onclick="" id="">Edit Task</button>
                </a>
                <button id="updateBtnHide" type="button" class="btn btn-accent btn-sm" onclick="closeTask();" id="submit_btn">Complete Task </button>
            </div>
        </div>
    </div>
</div>