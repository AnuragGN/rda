@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])
@section ('content')
    @include('common.page-header', ['pageTitle' => 'View Ticket', 'hcXlWidth' => 12])
    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-12 col-r-15 fund-advisors">
                        <div class="row">
                            <div class="col-lg-5 col-r-15">
                               <div class="row">
                                    <div class="col-12">
                                        <h4 class="page-subtitle">Ticket Detail</h4>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Subject:</span>
                                            </div>
                                            <div class="col-8">
                                                {{ $ticket->title }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Description:</span>
                                            </div>
                                            <div class="col-8" id="ticketDescDiv">
                                                {{ Str::limit(str_replace('&nbsp;', '', strip_tags($ticket->description)), 45) }}
                                                @if (strlen(strip_tags($ticket->description)) > 45)
                                                    <a href="javascript:void(0);"
                                                    onclick="descriptionViewLessMore('Show More')" data-child-id="statement-filter" class="btn btn-light btn-sm shadowed ml-0" style="background: #e6f4f7;">
                                                    Show More
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Ticket Type:</span>
                                            </div>
                                            <div class="col-7">
                                                <select class="form-control" id="ticket_type" name="ticket_type" onchange="updateTicket('category',this.value);">
                                                    <option value="0">Select Ticket Type</option>
                                                    @foreach ($categoryDropdown as $id => $category)
                                                        @php
                                                        $selected = '';
                                                        if ($id == $ticket->category) {
                                                            $selected = 'selected'; 
                                                        }
                                                        @endphp
                                                        <option value="{{ $id }}" {{ $selected }}>{{ $category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <i id="category_msg" class="fa fa-check" aria-hidden="true" style="color:green;display:none;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Ticket Status:</span>
                                            </div>
                                            <div class="col-7">
                                                <select class="form-control" id="ticket_status" 
                                                name="ticket_status" onchange="updateTicket('status',this.value);">
                                                    <option value="0">Select Ticket Status</option>
                                                    @foreach ($statusDropdown as $id => $status)
                                                        @php
                                                        $selected = '';
                                                        if ($id == $ticket->status) {
                                                            $selected = 'selected'; 
                                                        }
                                                        @endphp
                                                        <option value="{{ $id }}" {{ $selected }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <i id="status_msg" class="fa fa-check" aria-hidden="true" style="color:green;display:none;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 20px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Ticket Priority:</span>
                                            </div>
                                            <div class="col-7">
                                               <select class="form-control" id="ticket_priority" 
                                               name="ticket_priority" onchange="updateTicket('priority',this.value);">
                                                <option value="0">Select Ticket Priority</option>
                                                @foreach ($priorityDropdown as $id => $priority)
                                                    @php
                                                    $selected = '';
                                                    if ($id == $ticket->priority) {
                                                        $selected = 'selected'; 
                                                    }
                                                    @endphp
                                                    <option value="{{ $id }}" {{ $selected }}>{{ $priority }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <i id="priority_msg" class="fa fa-check" aria-hidden="true" style="color:green;display:none;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Fund:</span>
                                            </div>
                                            <div class="col-8">
                                                <a style="background: #e6f4f7;" target="_blnak" class="btn btn-light btn-sm shadowed ml-0" href="{{ route('fund', [$fund_id]) }}">{{ $fund_name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Assigned To:</span>
                                            </div>
                                            <div class="col-8">
                                                @if ($assignedToContact)
                                                    {{ $assignedToContact->first_name }} {{ $assignedToContact->last_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Created By:</span>
                                            </div>
                                            <div class="col-8">
                                                @if ($ticketCreatedBy)
                                                    {{ $ticketCreatedBy->first_name }} {{ $ticketCreatedBy->last_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Created On:</span>
                                            </div>
                                            <div class="col-8">
                                                <!-- {{ $ticket->created_at->format('d-m-Y H:i:s') }} -->
                                                {{ \App\Helpers\GnUtils::customDate($ticket->created_at) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Closing Date:</span>
                                            </div>
                                            <div class="col-8">
                                            @if (config('dropdown.status.' . $ticket->status) == 'Closed')
                                                @if ($ticket->closed_at)
                                                    <span>{{ \App\Helpers\GnUtils::customDate($ticket->closed_at) }}</span>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            @else
                                                <span>N/A</span>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 col-lg">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="page-subtitle">Ticket Comment 
                                            <span class="amount">

                                                <a onclick="ticketHistory({{ $ticket['id'] }},'{{ $ticket['title'] }}');" style="color:#0055a3;cursor:pointer;"><small><b><i> Ticket History </i></b></small></a>
                                            </span>
                                        </h4>
                                        <div class="row" id="scrollDiv"
                                            style="max-width: 800px; overflow: hidden;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); overflow-y: auto; max-height: 240px;">
                                            <div class="col" id="commentDiv">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <form id="uploadForm" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <label>Leave a comment <span style="color:red;">*</span></label>
                                            <textarea class="form-control" id="comment_text" name="comment_text" rows="3" placeholder="Comment.."></textarea>
                                            <small id="error_msg" style="color:red;"></small>
                                        </div>
                                        
                                        <div class="row" style="margin-top: 12px">
                                            <input type="file" multiple name="files[]" id="attachment" accept=".pdf, .doc, .docx">
                                        </div>

                                        <div class="row" style="margin-top: 12px">
                                            <input class="form-control2 checkbox-1x ml-1" type="checkbox" id="own_hisotry" name="own_hisotry"> &nbsp; For my own records
                                        </div>

                                        <div class="row" style="margin-top: 12px">
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                            <input type="hidden" name="created_by" value="{{ $ticket->created_by }}">

                                            <button onclick="addComment()" type="button" class="btn btn-accent">Submit</button>
                                        </div>
                                    </form>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
markAsRead('','{{ $ticket->id }}');
    setTimeout(function() {
        getComment();
    }, 100);

    setInterval(getComment,10000); //10 seconds

    function descriptionViewLessMore(type)
    {
        if (type == 'Show More') {
            var desc = `{!! str_replace('&nbsp;', '', $ticket->description) !!}` + '.'
                + `<br><a href="javascript:void(0);" onclick="descriptionViewLessMore('Show Less')" class="btn btn-light btn-sm shadowed ml-0" style="background: #e6f4f7;">Show Less</a>`;
        }
        if (type == 'Show Less') {
            var desc = `{{ Str::limit(str_replace('&nbsp;', '', strip_tags($ticket->description)), 45) }}`
                + `<a href="javascript:void(0);" onclick="descriptionViewLessMore('Show More')" class="btn btn-light btn-sm shadowed ml-0" style="background: #e6f4f7;">Show More</a>`;
        }
        $("#ticketDescDiv").html(desc);
    }

    function getComment() {

        var ticket_id = '{{ $ticket->id }}';

        $.ajax({
            type        : 'get',
            url         : "/m/ticket/get-comment",
            data: {
                "ticket_id": ticket_id,
                "type": ''
            },
            dataType: 'json',
            success     : function(data)
            {  
                var contact_id = '{{ $contact_id }}';
                var currentDate = new Date();
                var html = ``;
                if (data.length > 0) {
                    
                    for (var i in data) {

                        var created_by = data[i].created_by;
                        var className = (created_by == contact_id) ? 'timeline_right_cmt' : 'timeline_left_cmt';
                        //var created_at = moment(data[i].created_at).format('DD-MM-YYYY HH:mm');
                        var media_arr = data[i].media;

                        if (data[i].private == 1) {
                            var fafa_icon = '<i title="Private message" class="fa fa-user-secret"></i>';
                            var bg_color = '#f1f0d6';

                        } else {
                            var fafa_icon = '<i title="Ticket Log" class="nav-icon fas fa-file"></i>';
                            var bg_color = 'ebf1fc';
                        }  
                        html += `<div class="container_timeline1 ${className}" style="padding: 0 1px;content: none;!important">
                            <div class="timeline_content" style="background-color:${bg_color}">`;

                                html += `<p>${fafa_icon} ${data[i].comment}</p>`;

                                if (media_arr.length > 0) {

                                    for (var j in media_arr) {

                                        html += `<small><i class="fa fa-download" aria-hidden="true"></i>&nbsp<a title="${media_arr[j].name}" href="{{ asset('uploads/tickets/${media_arr[j].file_name}') }}" download>${media_arr[j].name}</a></small><br>`;
                                    }
                                }
                                html += `<p>
                                <small><i class="nav-icon fas fa-user"></i> ${data[i].comment_added_by}</small> 
                                <small style="float: right;"><i class="fa fa-clock-o" style="font-size:48px;color:red;"></i> ${data[i].created_at_format}</small>
                                </p>
                            </div>
                            </div>
                        </div>`;
                    }
                } else {
                    html += `
                        <p>
                           There are no comments.
                        </p>
                    `;
                }

                $("#commentDiv").html(html);

                var chatContainer = $('#scrollDiv');
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
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
            url         : "/m/ticket/add-comment",
            data        : formData,
            contentType: false,
            processData: false,
            success     : function(data)
            {  
                $('#uploadForm')[0].reset();
                getComment();
            }
        });
    }

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
                            url         : "/m/ticket/close",
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

    function updateTicket(field_type,field_value) {

        var ticket_id = '{{ $ticket->id }}';
        if(ticket_id) {

            if(field_value != 0) {
                
                $.ajax({
                    type: 'get',
                    url: "/m/ticket/update-ticket-detail",
                    data: {
                        "ticket_id": ticket_id,
                        "field_type": field_type,
                        "field_value": field_value
                    },
                    dataType: 'json',
                    success: function(data) {
                        
                        $("#"+field_type+"_msg").show();

                        setTimeout(function() {
                            $("#"+field_type+"_msg").hide();
                        }, 1000);
                    }
                });
            }
        }
    }

function ticketHistory(ticket_id,ticket_name){

    $.ajax({
        type: 'get',
        url: "/m/ticket/get-comment",
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
                    //var created_at = moment(data[i].created_at).format('DD-MM-YYYY HH:mm');
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
@endsection

