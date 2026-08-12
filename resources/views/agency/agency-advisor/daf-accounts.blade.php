<?php
if(!isset($search)) $search = null;
if(!isset($advanceSearch)) $advanceSearch = null;
?>
@extends('agency.layouts.main')

@section('content')
@include('common.page-header', ['pageTitle' => 'DAF Applications', 'hcXlWidth' => 12])

<style>
th.sortable {
    cursor: pointer;
    user-select: none;
}
</style>

<div class="container">
    <div class="form-wrapper form-last">

        {{-- Search Bar --}}
        <div class="form-group row align-items-center">
            <div class="col-xl-3 col-md-3 gn-form ">
                <select name="sponsor_id" class="form-control"
                        onchange="dafAccountsBySponsors()" id="id_sponsor_id">
                    <option value="">All DAF Sponsor</option>
                    @foreach($sponsors as $sponsor)
                        <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-2 col-md-3 d-none d-md-block">
                <a href="javascript:void(0);"
                   onclick="advanceSearchCollapsible(this)"
                   data-replace-id="id-advance-search">
                    Advance Search
                </a>
            </div>

            <div class="col-xl-3 col-md-3">
                <a href="{{route('agency-daf-accounts')}}"
                   id="clear_search"
                   style="display: none;">
                    Clear search
                </a>
            </div>
        </div>

        {{-- Advance Search --}}
        <div class="advance-search" id="id-advance-search" style="display:none;">
            <hr>

            <form action="{{ route('agency-daf-accounts') }}"
                  method="GET"
                  class="form-horizontal gn-form">

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right pr-0">
                        DAF Name
                    </label>
                    <div class="col-md-3">
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Full Name / First Name / Last Name"
                               value="{{ $advanceSearch['name'] ?? '' }}">
                    </div>

                    <label class="col-md-2 col-form-label text-right pr-0">
                        DAF ID
                    </label>
                    <div class="col-md-3">
                        <input type="text"
                               name="id"
                               class="form-control"
                               placeholder="DAF Account ID"
                               value="{{ $advanceSearch['id'] ?? '' }}"
                               onkeypress="return /^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/i.test(event.key)">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right pr-0">
                        Created Date
                    </label>
                    <div class="col-md-3">
                        <input type="text" id="id-date-range" class="form-control">
                        <input id="id-start-date"
                               name="start_date"
                               type="hidden"
                               value="{{ $advanceSearch['start_date'] ?? '' }}">
                        <input id="id-end-date"
                               name="end_date"
                               type="hidden"
                               value="{{ $advanceSearch['end_date'] ?? '' }}">
                    </div>

                    <label class="col-md-2 col-form-label text-right pr-0">
                        DAF Sponsor Sync Status
                    </label>
                    <div class="col-md-3">
                        <select name="sync_status"
                                class="form-control"
                                id="id_sync_status">
                            <option value="">All</option>
                            <option value="pending" {{ ($advanceSearch['sync_status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ ($advanceSearch['sync_status'] ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ ($advanceSearch['sync_status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ ($advanceSearch['sync_status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-2 col-md-3">
                        <button type="submit" class="btn btn-accent w-100">
                            Search
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div class="form-group row mt-3" style="overflow-x:auto;">
            <div class="col-md-12">

                <table class="table table-hover table-sm contact-table" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th data-type="string" class="sortable">DAF Id <i class="fas fa-sort"></i></th>
                            <th data-type="string" class="sortable">Name <i class="fas fa-sort"></i></th>
                            <th data-type="string" class="sortable">Status <i class="fas fa-sort"></i></th>
                            <th data-type="string" class="sortable">DAF Sponsor <i class="fas fa-sort"></i></th>
                            <th data-type="string" class="sortable">DAF Sponsor Sync <i class="fas fa-sort"></i></th>
                            <th data-type="string" class="sortable">Created At <i class="fas fa-sort"></i></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dafAccounts as $dafAccount)
                        
                            <tr>
                                <td>{{ $dafAccounts->firstItem() + $loop->index }}</td>
                                <td>{{ $dafAccount->id }}</td>
                                <td>{{ $dafAccount->donor_full_name }}</td>
                                <td>{{ ucfirst($dafAccount->status) }}</td>
                                <td>{{ $dafAccount->sponsor->name ?? 'Unknown' }}</td>
                                <td>{{ ucfirst($dafAccount->sponsor_sync) }}</td>
                                <td>{{ \App\Helpers\GnUtils::customDate($dafAccount->created_at) }}</td>
                                <td>
                                    <a href="{{ 
                                        $dafAccount->status === 'submitted'
                                            ? route('agency-daf-application-status', ['id' => $dafAccount->id])
                                            : route('agency-daf-account-info', ['id' => $dafAccount->id])
                                    }}"
                                        class="btn btn-accent btn-sm"
                                        target="_blank"
                                        rel="noopener noreferrer">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    No DAF Applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ✅ ORIGINAL CUSTOM PAGINATION RESTORED --}}
                @if ($dafAccounts->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        <ul class="pagination mb-0">

                            <li class="page-item {{ $dafAccounts->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                   href="{{ $dafAccounts->previousPageUrl() }}">«</a>
                            </li>

                            @foreach ($dafAccounts->getUrlRange(1, $dafAccounts->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $dafAccounts->currentPage() ? 'active' : '' }}">
                                    <a class="page-link"
                                       href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $dafAccounts->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link"
                                   href="{{ $dafAccounts->nextPageUrl() }}">»</a>
                            </li>

                        </ul>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
<script>
$(document).ready(function () {

    /* =========================================================
       INITIAL SEARCH STATE
    ========================================================== */

    const sponsorSearch = "{{ $search ?? '' }}";

    const advanceSearch = {
        name: "{{ $advanceSearch['name'] ?? '' }}",
        id: "{{ $advanceSearch['id'] ?? '' }}",
        startDate: "{{ $advanceSearch['start_date'] ?? '' }}",
        endDate: "{{ $advanceSearch['end_date'] ?? '' }}",
        syncStatus: "{{ $advanceSearch['sync_status'] ?? '' }}"
    };

    if (sponsorSearch) {
        $('#id_sponsor_id').val(sponsorSearch);
        $('#clear_search').show();
    }

    const hasAdvanceSearch =
        advanceSearch.name ||
        advanceSearch.id ||
        advanceSearch.startDate ||
        advanceSearch.syncStatus;

    if (hasAdvanceSearch) {
        $('#id_sync_status').val(advanceSearch.syncStatus);
        $('#id-advance-search').show();
        $('#clear_search').show();
    }

    /* =========================================================
       DATE RANGE PICKER
    ========================================================== */

    const formatUI = 'MM-DD-YYYY';
    const formatDB = 'YYYY-MM-DD';

    let start = moment(advanceSearch.startDate, formatDB);
    let end   = moment(advanceSearch.endDate, formatDB);

    if (!start.isValid() || !end.isValid()) {
        start = moment().subtract(10, 'years');
        end   = moment();
    }

    $('#id-date-range').val(start.format(formatUI) + ' - ' + end.format(formatUI));
    $('#id-start-date').val(start.format(formatDB));
    $('#id-end-date').val(end.format(formatDB));

    $('#id-date-range').daterangepicker({
        locale: { format: formatUI },
        opens: 'left',
        minYear: 2000,
        maxYear: moment().year()
    }, function (s, e) {
        $('#id-start-date').val(s.format(formatDB));
        $('#id-end-date').val(e.format(formatDB));
    });

    /* =========================================================
       TABLE SORTING
    ========================================================== */

    let sortDirection = {};

    $('#myTable thead th.sortable').on('click', function () {

        const $th = $(this);
        const table = $th.closest('table');
        const tbody = table.find('tbody');
        const columnIndex = $th.index();
        const type = $th.data('type') || 'string';

        sortDirection[columnIndex] = !sortDirection[columnIndex];
        const asc = sortDirection[columnIndex];

        // Reset all icons
        table.find('th i')
            .removeClass('fa-sort-up fa-sort-down')
            .addClass('fa-sort');

        // Update clicked icon
        $th.find('i')
            .removeClass('fa-sort')
            .addClass(asc ? 'fa-sort-up' : 'fa-sort-down');

        const rows = tbody.find('tr').get();

        rows.sort(function (a, b) {

            let A = $(a).children('td').eq(columnIndex).text().trim();
            let B = $(b).children('td').eq(columnIndex).text().trim();

            if (type === 'number') {
                A = parseFloat(A) || 0;
                B = parseFloat(B) || 0;
            } 
            else if (type === 'date') {
                A = new Date(A);
                B = new Date(B);
            } 
            else {
                A = A.toLowerCase();
                B = B.toLowerCase();
            }

            if (A < B) return asc ? -1 : 1;
            if (A > B) return asc ? 1 : -1;
            return 0;
        });

        $.each(rows, function (_, row) {
            tbody.append(row);
        });
    });

});


/* =========================================================
   SPONSOR FILTER (kept outside for global access)
========================================================== */

function dafAccountsBySponsors() {
    const q = $.trim($('#id_sponsor_id').val());
    if (!q) return;

    window.location.href =
        "{{ route('agency-daf-accounts') }}" +
        "?q=" + encodeURIComponent(q);
}


/* =========================================================
   ADVANCE SEARCH TOGGLE
========================================================== */

function advanceSearchCollapsible(e) {

    const tag = e.getAttribute('data-replace-id');
    if (!tag) return;

    const $target = $('#' + tag);
    if (!$target.length) return;

    const extraCls = 'collapsible-replace-visible';

    if ($target.is(':visible')) {
        $(e).removeClass(extraCls);
        $target.hide(400);
    } else {
        $(e).addClass(extraCls);
        $target.show(400);
    }
}
</script>


@endsection
