<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-file-contract"></i> DAF Applications</div>
  </div>
  <div class="sec-body">
    <div class="mb-3">
      <select class="sort-sel" id="daf-sponsor-filter" style="width:100%;max-width:260px" onchange="renderDafList()">
        <option value="">All Sponsors</option>
        @foreach($sponsors as $sp)
          <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
        @endforeach
      </select>
    </div>
    <div id="daf-list">
      @forelse(array_slice($dafApps, 0, 5) as $app)
      <div class="daf-item">
        <div class="flex-grow-1">
          <div class="daf-name">{{ $app['name'] }}</div>
          <div class="daf-meta">{{ $app['sponsor'] }} &bull; {{ $app['date'] }}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          @php
            $badgeMap = ['approved'=>'b-approved','submitted'=>'b-submitted','pending'=>'b-pending','review'=>'b-review'];
            $labelMap = ['approved'=>'Approved','submitted'=>'Submitted','pending'=>'Pending','review'=>'Under Review'];
          @endphp
          <span class="badge-pill {{ $badgeMap[$app['status']] ?? 'b-closed' }}">{{ $labelMap[$app['status']] ?? $app['status'] }}</span>
          <button class="icon-btn view" aria-label="View"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
      @empty
      <div class="empty-state"><i class="fa-solid fa-file-contract"></i>No applications found.</div>
      @endforelse
    </div>
    @if(count($dafApps) > 5)
    <div style="border-top:1px solid var(--border);margin-top:.5rem;padding-top:.65rem;text-align:center">
      <a href="" class="view-all-link">
        <i class="fa-solid fa-list"></i> View All Applications
      </a>
    </div>
    @endif
  </div>
</div>