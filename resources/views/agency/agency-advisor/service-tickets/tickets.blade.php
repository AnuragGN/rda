@extends('agency.layouts.main')

@section('content')

@include('common.page-header', ['pageTitle' => 'Service Tickets', 'hcXlWidth' => 12])

<div class="container history-container">
    <div class="form-wrapper form-last">
        <div class="row">

            {{-- ================= LEFT SIDE ================= --}}
            <div class="col-xl-7 col-r-15">

                {{-- Page Title --}}
                <div class="page-title mt-2">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-xl-7 col-sm-12">
                                <h3>Ticket List</h3>
                            </div>
                            <div class="col-xl-5 col-sm-12 text-xl-right mt-2 mt-xl-0">

                                <a href="javascript:void(0);"
                                   onclick="sageCollapsible(this)"
                                   data-child-id="statement-filter"
                                   class="btn btn-light btn-sm shadowed mr-2"
                                   style="background-color: #e6f4f7;">
                                    Filter <i class="fas fa-filter"></i>
                                </a>

                                <a href="{{ route('agency-service-ticket-create') }}"
                                   class="btn btn-accent btn-sm">
                                    Create Ticket <i class="nav-icon fas fa-wrench"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= FILTER FORM ================= --}}
                <form action="{{ route('agency-ticket') }}" method="GET" id="form-history-filter">

                    <div class="row mt-3" id="statement-filter">
                        <div class="col-12">
                            <div class="filter-row">

                                {{-- Search + Sponsor --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label><small><b>Search</b></small></label>
                                        <input id="search_ticket"
                                               name="search_ticket"
                                               value="{{ request()->search_ticket }}"
                                               type="text"
                                               class="form-control"
                                               placeholder="Search Ticket by Id, Text..">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label><small><b>Sponsor</b></small></label>
                                        <select class="form-control"
                                                name="sponsor_id"
                                                id="sponsor_id"
                                                onchange="getTicketList();">
                                            <option value="">All Sponsor</option>
                                            @foreach($sponsors as $sponsor)
                                                <option value="{{ $sponsor->id }}"
                                                    {{ (request('sponsor_id') == $sponsor->id || (!request()->has('sponsor_id') && $sponsor->id == $preferredCharityId)) ? 'selected' : '' }}>
                                                    {{ $sponsor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Status / Priority / Type --}}
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label><small><b>Ticket Status</b></small></label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">All Status</option>
                                            @foreach($statusDropdown as $key => $value)
                                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label><small><b>Ticket Priority</b></small></label>
                                        <select class="form-control" name="priority" id="priority">
                                            <option value="">All Priority</option>
                                            @foreach($priorityDropdown as $key => $value)
                                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label><small><b>Ticket Type</b></small></label>
                                        <select class="form-control" name="category" id="category">
                                            <option value="">All Ticket Type</option>
                                            @foreach($categoryDropdown as $key => $value)
                                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <button type="submit" class="btn btn-accent w-100">
                                            Search
                                        </button>
                                    </div>

                                    <div class="col-md-3">
                                        <a href="{{ route('agency-ticket') }}"
                                        id="clear_search"
                                        class="{{ request()->hasAny(['search_ticket','sponsor_id','status','priority','category']) ? '' : 'd-none' }}">
                                            Clear search
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

                {{-- ================= TABLE ================= --}}
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-sm contact-table" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>TId</th>
                                <th>Subject</th>
                                <th>Assigned To</th>
                                <th>Ticket Detail</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="fa_ticket_div">

                            @forelse($myTicket as $ticket)
                                <tr>
                                    <td>{{ $myTicket->firstItem() + $loop->index }}</td>
                                    <td>{{ $ticket->id }}</td>
                                    <td style="width:120px;">
                                        <a href="{{ route('agency-service-ticket-view', $ticket->id) }}">
                                            <small>{{ $ticket->title }}</small>
                                        </a>
                                    </td>
                                   <td>
                                        <small>
                                            {{
                                                optional($ticket->secondaryAssignee->contact ?? $ticket->primaryAssignee)->first_name
                                                ? optional($ticket->secondaryAssignee->contact ?? $ticket->primaryAssignee)->first_name . ' ' .
                                                optional($ticket->secondaryAssignee->contact ?? $ticket->primaryAssignee)->last_name
                                                : 'NA'
                                            }}
                                        </small>
                                    </td>

                                    <td class="align-middle">
                                        <div class="small">
                                            <div>
                                                <strong>Created At:</strong> {{ \App\Helpers\GnUtils::customDate($ticket->created_at) }}
                                            </div>
                                            <div>
                                                <strong>Status:</strong> {{ $ticket->status_label }}
                                                <span class="mx-2 text-muted">|</span>
                                                <strong>Priority:</strong> {{ $ticket->priority_label }}
                                            </div>
                                            <div>
                                                <strong>Ticket Type:</strong> {{ $ticket->category_label }}

                                                <!-- @if($ticket->work_status_label)
                                                    <strong>Work Status:</strong> {{ $ticket->work_status_label }}
                                                @endif -->
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="hidden" class="ticket-class" value="{{ $ticket->status }}">

                                        <a href="{{ route('agency-service-ticket-view', $ticket->id) }}" title="View Ticket">
                                            <i class="fa fa-eye" style="color:#00758f;"></i>
                                        </a> |

                                        <a href="{{ route('agency-services-edit-ticket', $ticket->id) }}" title="Edit Ticket">
                                            <i class="fa fa-edit" style="color:#00758f;"></i>
                                        </a> |

                                        <a onclick="deleteTicket({{ $ticket->id }},'service-page');" title="Archive Ticket">
                                            <i class="fa fa-archive" style="color:#00758f;cursor:pointer;"></i>
                                        </a>

                                        <br>
                                        <small>
                                            <a onclick="ticketHistory({{ $ticket->id }},'{{ $ticket->title }}');"
                                               style="color:#0055a3;cursor:pointer;" title="Ticket History">
                                                <b><i>Ticket History</i></b>
                                            </a>
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No Tickets found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ================= PAGINATION ================= --}}
                @if ($myTicket->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        <ul class="pagination mb-0">
                            <li class="page-item {{ $myTicket->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $myTicket->previousPageUrl() }}">«</a>
                            </li>

                            @foreach ($myTicket->getUrlRange(1, $myTicket->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $myTicket->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $myTicket->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $myTicket->nextPageUrl() }}">»</a>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>

            {{-- ================= RIGHT SIDE GRAPH ================= --}}
            <div class="col-xl-5 col-r-15 mt-4 mt-xl-0">

                <h3 class="page-subtitle mt-3">Ticket Graph</h3>

                <select class="form-control mb-3"
                        onchange="generateTicketChart(this.value);"
                        id="chart_id">
                    @foreach($charts as $chartKey => $chartVal)
                        <option value="{{ $chartKey }}"
                            {{ $chartKey == $preferredChartType ? 'selected' : '' }}>
                            {{ $chartVal }}
                        </option>
                    @endforeach
                </select>

                <canvas id="chartContainer"></canvas>

            </div>

        </div>
    </div>
</div>

@include('agency.agency-advisor.common-script')

<script>
window.currentTicketData = [];
let ticketChart = null;

var statusCounts = @json($statusCounts);

Object.keys(statusCounts).forEach(function(key) {
    window.currentTicketData.push({
        status_name: key.replace(/\b\w/g, l => l.toUpperCase()),
        total: statusCounts[key]
    });
});

document.addEventListener("DOMContentLoaded", function() {
    generateTicketChart("{{ $preferredChartType }}", window.currentTicketData);
});
</script>

@endsection