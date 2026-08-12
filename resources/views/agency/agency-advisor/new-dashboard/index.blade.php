@extends(\App\Helpers\GnUtils::getUserView('layouts.main'), [
    'container'       => 'container-fluid px-0',
    'agencyContainer' => 'container-fluid px-0',
])

@section('title', 'Financial Advisor Dashboard')

{{-- ── Dashboard-specific CSS (scoped, no global side-effects) ── --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/advisor-dashboard.css') }}">
@endpush

@section('content')
<div class="adv-dashboard px-3 px-md-4 py-3">

    {{-- Page heading --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <div class="page-title">
                <i class="fa-solid fa-gauge-high me-2" style="color:var(--adv-teal);font-size:1.1rem"></i>
                Financial Advisor Dashboard
            </div>
            <div class="page-sub">Welcome back — here's your portfolio overview</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="adv-last-updated" id="last-updated-val" style="font-size:.72rem;color:var(--adv-muted);">
                <i class="fa-regular fa-clock me-1"></i>
                {{ now()->format('M j, Y g:i A') }}
            </span>
            <button id="refresh-btn" class="btn-primary-action" style="padding:.35rem .8rem;font-size:.75rem;">
                <i class="fa-solid fa-rotate-right" id="refresh-icon"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Filter bar --}}
    @include('agency.agency-advisor.new-dashboard.partials.filter-bar')

    {{-- KPI cards --}}
    @include('agency.agency-advisor.new-dashboard.partials.kpi-cards', ['kpis' => $kpis])

    {{-- Charts row: AUM trend + Grant pipeline --}}
    <div class="row g-3 mb-3 align-items-stretch">
        <div class="col-lg-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.aum-chart', ['sponsors' => $sponsors])
        </div>
        <div class="col-lg-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.pipeline', [
                'pipeline'     => $pipeline,
                'pendingTotal' => $pendingTotal,
            ])
        </div>
    </div>

    {{-- Donor Funds + DAF Applications --}}
    <div class="row g-3 mb-3 align-items-stretch">
        <div class="col-md-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.fund-tree', [
                'fundsBySponsor' => $fundsBySponsor,
                'sponsors'       => $sponsors,
            ])
        </div>
        <div class="col-md-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.daf-apps', [
                'dafApps'  => $dafApps,
                'sponsors' => $sponsors,
            ])
        </div>
    </div>

    {{-- Service Requests (full width) --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            @include('agency.agency-advisor.new-dashboard.partials.service-requests', [
                'tickets'     => $tickets,
                'ticketStats' => $ticketStats,
                'sponsors'    => $sponsors,
            ])
        </div>
    </div>

    {{-- Recommendations + Activity Feed --}}
    <div class="row g-3 mb-3 align-items-stretch">
        <div class="col-md-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.recommendations', [
                'recommendations' => $recommendations,
                'sponsors'        => $sponsors,
            ])
        </div>
        <div class="col-md-6 d-flex flex-column">
            @include('agency.agency-advisor.new-dashboard.partials.activity-feed', [
                'activity' => $activity,
            ])
        </div>
    </div>

    {{-- Institutional Claims (full width) --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            @include('agency.agency-advisor.new-dashboard.partials.claims-table', [
                'sponsors' => $sponsors,
            ])
        </div>
    </div>

</div>{{-- /.adv-dashboard --}}
@endsection

@push('scripts')
{{-- Inject server data for JS charts --}}
<script>
window.DASHBOARD_DATA = {
    sponsorBalances : @json($sponsorBalances),
    ticketStats     : @json($ticketStats),
};
window.SPONSORS_DATA = @json($sponsors);
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
