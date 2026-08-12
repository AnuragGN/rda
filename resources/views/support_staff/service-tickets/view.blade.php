@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))
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
                                            <div class="col-8">
                                                {{ $categoryDropdown[$ticket->category] }}
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
                                            <div class="col-8">
                                                {{ $statusDropdown[$ticket->status] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Ticket Priority:</span>
                                            </div>
                                            <div class="col-8">
                                                {{ $priorityDropdown[$ticket->priority] }}
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
                                                {{ $fund_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Work Status:</span>
                                            </div>
                                            <div class="col-7">
                                                <select class="form-control" id="work_status" 
                                                name="work_status" onchange="updateWorkTicket(this.value);">
                                                    <option value="0">Select Work Status</option>
                                                    @foreach ($workStatusDropdown as $id => $status)
                                                        @php
                                                        $selected = '';
                                                        if ($id == $secAssign->status) {
                                                            $selected = 'selected'; 
                                                        }
                                                        @endphp
                                                        <option value="{{ $id }}" {{ $selected }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <i id="work_status_msg" class="fa fa-check" aria-hidden="true" style="color:green;display:none;margin-top: 10px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <span>Assigned By:</span>
                                            </div>
                                            <div class="col-8">
                                                {{ $primaryAssign->first_name }} {{ $primaryAssign->last_name }}
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
                                            <span class="amount" style="font-size: 16px;">
                                                <a onclick="ticketHistory({{ $ticket['id'] }},'{{ $ticket['title'] }}');" style="color:#0055a3;cursor:pointer;">
                                                    <small>
                                                        <i title="View Ticket" class="fa fa-eye" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i>
                                                        <i><b>Ticket History</b></i> 
                                                    </small>
                                                </a>
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
                                            <input type="hidden" name="created_by" value="{{ $ticket->assigned_to }}">

                                            <input type="hidden" name="ticket_assignee_id" value="{{ $secAssign->id }}">

                                            <button onclick="addComment()" type="button" class="btn btn-accent">Submit</button>
                                            <span id="view_chat_record_div"></span>
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
@include('agency.agency-advisor.common-script')
<script> 
markAsRead('','{{ $ticket->id }}');
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
    $("#ticketDescDiv").html(desc)
}

function updateWorkTicket(work_status) {

    var ticket_id           = '{{ $ticket->id }}';
    var ticket_assignee_id  = '{{ $secAssign->id }}';
    if(ticket_id !='' && ticket_assignee_id !='') {

        $.ajax({
            type: 'get',
            url: "/m/support-staff/ticket/update-ticket-detail",
            data: {
                "ticket_id": ticket_id,
                "ticket_assignee_id": ticket_assignee_id,
                "work_status": work_status
            },
            dataType: 'json',
            success: function(data) {
                
                $("#work_status_msg").show();

                setTimeout(function() {
                   $("#work_status_msg").hide();
                }, 1000);
            }
        });
    }
}

setTimeout(function() {
    getComment();
}, 100);

setInterval(getComment,10000); //10 seconds

function getComment() {

    var ticket_id = '{{ $ticket->id }}';

    $.ajax({
        type: 'get',
        url: "/m/agency/ticket/get-comment",
        data: {
            "ticket_id": ticket_id,
            "type": ''
        },
        dataType: 'json',
        success: function(data) {
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
                    </p>`;
            }
            $("#commentDiv").html(html);
            var chatContainer = $('#scrollDiv');
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }
    });
}
</script>
@endsection

