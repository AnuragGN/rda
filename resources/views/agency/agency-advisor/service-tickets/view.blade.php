@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])
@section ('content')
    @include('common.page-header', ['pageTitle' => 'View Ticket', 'hcXlWidth' => 12])
    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-12 col-r-15 fund-advisors">
                        <div class="row">

                            {{-- LEFT: Ticket Detail --}}
                            <div class="col-lg-5 col-r-15">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <h4 class="page-subtitle">Ticket Detail</h4>
                                    </div>
                                </div>

                                {{-- Subject --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Subject:</span></div>
                                    <div class="col-8">{{ $ticket->title }}</div>
                                </div>

                                {{-- Description --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Description:</span></div>
                                    <div class="col-8" id="ticketDescDiv">
                                        {{ Str::limit(str_replace('&nbsp;', '', strip_tags($ticket->description)), 45) }}
                                        @if (strlen(strip_tags($ticket->description)) > 45)
                                            <a href="javascript:void(0);"
                                                onclick="descriptionViewLessMore('Show More')"
                                                class="btn btn-light btn-sm shadowed ml-0"
                                                style="background:#e6f4f7;">Show More</a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Ticket Type --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Ticket Type:</span></div>
                                    <div class="col-7">
                                        <select class="form-control" id="ticket_type" name="ticket_type"
                                            onchange="updateTicket('category', this.value);">
                                            <option value="0">Select Ticket Type</option>
                                            @foreach ($categoryDropdown as $id => $category)
                                                <option value="{{ $id }}" @selected($id == $ticket->category)>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <i id="category_msg" class="fa fa-check" aria-hidden="true"
                                            style="color:green;display:none;margin-top:10px;"></i>
                                    </div>
                                </div>

                                {{-- Ticket Status --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Ticket Status:</span></div>
                                    <div class="col-7">
                                        <select class="form-control" id="ticket_status" name="ticket_status"
                                            onchange="updateTicket('status', this.value);">
                                            <option value="0">Select Ticket Status</option>
                                            @foreach ($statusDropdown as $id => $status)
                                                <option value="{{ $id }}" @selected($id == $ticket->status)>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <i id="status_msg" class="fa fa-check" aria-hidden="true"
                                            style="color:green;display:none;margin-top:10px;"></i>
                                    </div>
                                </div>

                                {{-- Ticket Priority --}}
                                <div class="row mb-3">
                                    <div class="col-4"><span>Ticket Priority:</span></div>
                                    <div class="col-7">
                                        <select class="form-control" id="ticket_priority" name="ticket_priority"
                                            onchange="updateTicket('priority', this.value);">
                                            <option value="0">Select Ticket Priority</option>
                                            @foreach ($priorityDropdown as $id => $priority)
                                                <option value="{{ $id }}" @selected($id == $ticket->priority)>
                                                    {{ $priority }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <i id="priority_msg" class="fa fa-check" aria-hidden="true"
                                            style="color:green;display:none;margin-top:10px;"></i>
                                    </div>
                                </div>

                                {{-- Fund --}}
                                @if ($fund_name)
                                    <div class="row mb-2">
                                        <div class="col-4"><span>Fund:</span></div>
                                        <div class="col-8">
                                            <a style="background:#e6f4f7;" target="_blank"
                                                class="btn btn-light btn-sm shadowed ml-0"
                                                href="{{ route('agency-fund', [$fund_id]) }}">
                                                {{ $fund_name }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                {{-- Primary Assignee --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Primary Assignee:</span></div>
                                    <div class="col-8">
                                        {{ $primaryAssign ? $primaryAssign->first_name . ' ' . $primaryAssign->last_name : 'N/A' }}
                                    </div>
                                </div>

                                {{-- Secondary Assignee --}}
                                <div class="row mb-3">
                                    <div class="col-4"><span>Assignee:</span></div>
                                    <div class="col-7">
                                        <select class="form-control" id="assign_to" name="assign_to"
                                            onchange="updateTicket('assigned_to', this.value);">
                                            <option value="0">Select Assignee</option>
                                            @foreach ($assigneeList as $assign)
                                                <option value="{{ $assign->contact_id }}"
                                                    @selected($assign->contact_id == $secondary_assigned_id)>
                                                    {{ $assign->first_name }} {{ $assign->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <i id="assigned_to_msg" class="fa fa-check" aria-hidden="true"
                                            style="color:green;display:none;margin-top:10px;"></i>
                                    </div>
                                </div>

                                {{-- Created By --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Created By:</span></div>
                                    <div class="col-8">
                                        @if ($created_by)
                                            <a style="background:#e6f4f7;" target="_blank"
                                                class="btn btn-light btn-sm shadowed ml-0"
                                                href="{{ route('agency-client-detail', ['id' => $ticket->created_by]) }}">
                                                {{ $created_by->first_name }} {{ $created_by->last_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Created On --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Created On:</span></div>
                                    <div class="col-8">
                                        {{ \App\Helpers\GnUtils::customDate($ticket->created_at) }}
                                    </div>
                                </div>

                                {{-- Closing Date --}}
                                <div class="row mb-2">
                                    <div class="col-4"><span>Closing Date:</span></div>
                                    <div class="col-8">
                                        @if (config('dropdown.status.' . $ticket->status) === 'Closed' && $ticket->closed_on)
                                            <span>{{ \App\Helpers\GnUtils::customDate($ticket->closed_on) }}</span>
                                        @else
                                            <span class="text-muted">NA</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Comments --}}
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="page-subtitle">
                                            Ticket Comment
                                            <span class="amount" style="font-size:16px;">
                                                <a title="Edit Ticket"
                                                    href="{{ route('agency-services-edit-ticket', $ticket->id) }}"
                                                    style="color:#0055a3;cursor:pointer;">
                                                    <small>
                                                        <i class="fa fa-edit" style="color:#00758f;"></i>
                                                        <i><b>Edit Ticket</b></i>
                                                    </small>
                                                </a>
                                                |
                                                <a onclick="ticketHistory({{ $ticket->id }},'{{ $ticket->title }}');"
                                                    style="color:#0055a3;cursor:pointer;">
                                                    <small>
                                                        <i class="fa fa-eye" style="color:#00758f;"></i>
                                                        <i><b>Ticket History</b></i>
                                                    </small>
                                                </a>
                                                @if (config('dropdown.status.' . $ticket->status) !== 'Closed')
                                                    |
                                                    <a onclick="closeTicket({{ $ticket->id }})"
                                                        style="color:red;cursor:pointer;">
                                                        <small>
                                                            <i class="fas fa-times-circle"></i>
                                                            <i><b> Close Ticket</b></i>
                                                        </small>
                                                    </a>
                                                @endif
                                            </span>
                                        </h4>

                                        <div class="row" id="scrollDiv"
                                            style="max-width:800px;overflow-y:auto;max-height:240px;box-shadow:0 0 10px rgba(0,0,0,0.1);">
                                            <div class="col" id="commentDiv"></div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <form id="uploadForm" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <label>Leave a comment <span style="color:red;">*</span></label>
                                            <textarea class="form-control" id="comment_text" name="comment_text"
                                                rows="3" placeholder="Comment.."></textarea>
                                            <small id="error_msg" style="color:red;"></small>
                                        </div>

                                        <div class="row mt-3">
                                            <input type="file" multiple name="files[]" id="attachment"
                                                accept=".pdf,.doc,.docx">
                                        </div>

                                        <div class="row mt-3">
                                            <input class="form-control2 checkbox-1x ml-1" type="checkbox"
                                                id="own_hisotry" name="own_hisotry">
                                            &nbsp; For my own records
                                        </div>

                                        <div class="row mt-3">
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                            <input type="hidden" name="created_by" value="{{ $ticket->assigned_to }}">
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
markAsRead('', '{{ $ticket->id }}');

function descriptionViewLessMore(type) {
    var desc = '';
    if (type === 'Show More') {
        desc = `{!! str_replace('&nbsp;', '', $ticket->description) !!}`
            + `<br><a href="javascript:void(0);" onclick="descriptionViewLessMore('Show Less')" class="btn btn-light btn-sm shadowed ml-0" style="background:#e6f4f7;">Show Less</a>`;
    } else {
        desc = `{{ Str::limit(str_replace('&nbsp;', '', strip_tags($ticket->description)), 45) }}`
            + `<a href="javascript:void(0);" onclick="descriptionViewLessMore('Show More')" class="btn btn-light btn-sm shadowed ml-0" style="background:#e6f4f7;">Show More</a>`;
    }
    $('#ticketDescDiv').html(desc);
}

function sendTicketUpdate(ticket_id, field_type, field_value) {
    $.ajax({
        type: 'get',
        url: "{{ route('update-agency-ticket-detail') }}",
        data: { ticket_id: ticket_id, field_type: field_type, field_value: field_value },
        dataType: 'json',
        success: function () {
            $('#' + field_type + '_msg').show();
            setTimeout(function () { $('#' + field_type + '_msg').hide(); }, 1000);
        }
    });
}

function updateTicket(field_type, field_value) {
    var ticket_id = '{{ $ticket->id }}';
    if (!ticket_id) return;
    if (field_value == 0 && field_type !== 'assigned_to') return;

    if (field_type === 'assigned_to') {
        $.confirm({
            columnClass: 'medium',
            title: '',
            content: 'Are you sure you want to assign this ticket to someone else?',
            buttons: {
                no:  { text: 'No',  btnClass: 'btn-light', keys: ['shift'], action: function () {} },
                yes: { text: 'Yes', btnClass: 'btn-accent', keys: ['enter'], action: function () {
                    sendTicketUpdate(ticket_id, field_type, field_value);
                }}
            }
        });
    } else {
        sendTicketUpdate(ticket_id, field_type, field_value);
    }
}

setTimeout(getComment, 100);
setInterval(getComment, 10000);

function getComment() {
    var ticket_id = '{{ $ticket->id }}';
    $.ajax({
        type: 'get',
        url: "{{ route('service-ticket-get-comment') }}",
        data: { ticket_id: ticket_id, type: '' },
        dataType: 'json',
        success: function (data) {
            var contact_id = '{{ $contact_id }}';
            var html = '';

            if (data.length > 0) {
                for (var i in data) {
                    var created_by   = data[i].created_by;
                    var className    = (created_by == contact_id) ? 'timeline_right_cmt' : 'timeline_left_cmt';
                    var media_arr    = data[i].media;
                    var fafa_icon, bg_color;

                    if (data[i].private == 1) {
                        fafa_icon = '<i title="Private message" class="fa fa-user-secret"></i>';
                        bg_color  = '#f1f0d6';
                    } else {
                        fafa_icon = '<i title="Ticket Log" class="nav-icon fas fa-file"></i>';
                        bg_color  = '#ebf1fc';
                    }

                    html += `<div class="container_timeline1 ${className}" style="padding:0 1px;">
                        <div class="timeline_content" style="background-color:${bg_color}">
                            <p>${fafa_icon} ${data[i].comment}</p>`;

                    if (media_arr.length > 0) {
                        for (var j in media_arr) {
                            html += `<small><i class="fa fa-download"></i>&nbsp;<a title="${media_arr[j].name}" href="{{ asset('uploads/tickets/') }}${media_arr[j].file_name}" download>${media_arr[j].name}</a></small><br>`;
                        }
                    }

                    html += `<p>
                                <small><i class="nav-icon fas fa-user"></i> ${data[i].comment_added_by}</small>
                                <small style="float:right;"><i class="fa fa-clock-o"></i> ${data[i].created_at_format}</small>
                            </p>
                        </div>
                    </div>`;
                }
            } else {
                html = '<p class="p-2 text-muted">There are no comments.</p>';
            }

            $('#commentDiv').html(html);
            var chatContainer = $('#scrollDiv');
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }
    });
}
</script>
@endsection
