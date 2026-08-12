<?php
$pageTitle = "Services";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-12 col-r-15">
                    <h3 class="page-subtitle mt-2">Services
                        <span></span>
                        <a>
                            <label for="task_type" class="sr-only">Filter by Task Type:</label>
                            <select class="form-control" id="task_type" name="task_type" onchange="get_task_list();">
                                <option value="0">All</option>
                                <option value="Event">Event</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Notes">Notes</option>
                            </select>
                        </a>
                        <span></span>
                        <a href="{{route('agency-services-create-task')}}" class="btn btn-accent btn-sm">
                            Create Task
                        </a>
                    </h3>
                    <table class="table-pending-grants">
                        <tbody id='taskDiv'></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script>

get_task_list();
function get_task_list()
{  
    var seach_type = $("#task_type").val();
    if(seach_type == undefined){
        seach_type = 0;
    }
    // alert(seach_type);
    $.ajax({
        type        : 'GET',
        url         : "/m/agency/task-list",
        data        : {'seach_type':seach_type},
        success     : function(data)
        {  
        $("#taskDiv").html(data);
        }
    });
}

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
                            get_task_list();
                        }
                    });
                }
            }
        }
    });
}

function getTaskDetail(taskId)
{
    $.ajax({
        type        : 'GET',
        url         : "/m/agency/task-detail",
        data        : {'taskId':taskId},
        success     : function(data)
        {  
            $("#exampleModal").modal('show');
            $("#TaskDetailDiv").html(data);

            var statusId = $("#hyd_status_id").val();
            $("#updateBtnHide").show();
            if(statusId == 'Close')
            {
                $("#updateBtnHide").hide();
            }
        }
    });
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
                            get_task_list();
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
                <button id="updateBtnHide" type="button" class="btn btn-accent btn-sm" onclick="closeTask();" id="submit_btn">Close Task </button>
            </div>
        </div>
    </div>
</div>
@endsection
 
