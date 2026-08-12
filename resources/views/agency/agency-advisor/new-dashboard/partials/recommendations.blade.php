<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-hand-holding-heart"></i> Pending Recommendations</div>
    <div class="sec-actions">
      <select class="head-sel" aria-label="Filter by sponsor">
        <option value="">All Sponsors</option>
        @foreach($sponsors as $sp)
          <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="sec-body">
    @forelse(array_slice($recommendations, 0, 5) as $rec)
    <div class="rec-item">
      <div class="d-flex justify-content-between align-items-start gap-2">
        <div>
          <div class="rec-org">{{ $rec['org'] }}</div>
          <div class="rec-amount">${{ number_format($rec['amount'], 2) }}</div>
          <div class="rec-meta">{{ $rec['donor'] }} &bull; {{ $rec['fund'] }} &bull; {{ $rec['date'] }}</div>
          <div style="margin-top:4px">
            @php
              $recBadge = ['submitted'=>['class'=>'b-submitted','label'=>'Submitted'],'approved'=>['class'=>'b-approved','label'=>'Approved'],'cancelled'=>['class'=>'b-rejected','label'=>'Cancelled'],'paid'=>['class'=>'b-disbursed','label'=>'Paid']];
              $rb = $recBadge[$rec['status']] ?? ['class'=>'b-pending','label'=>ucfirst($rec['status'])];
            @endphp
            <span class="badge-pill {{ $rb['class'] }}">{{ $rb['label'] }}</span>
          </div>
        </div>
        <a href="#" class="btn-view-ticket flex-shrink-0"><i class="fa-regular fa-eye me-1"></i>View</a>
      </div>
    </div>
    @empty
    <div class="empty-state"><i class="fa-solid fa-hand-holding-heart"></i>No recommendations found.</div>
    @endforelse
    @if(count($recommendations) > 5)
    <div style="border-top:1px solid var(--border);margin-top:.5rem;padding-top:.65rem;text-align:center">
      <a href="" class="view-all-link">
        <i class="fa-solid fa-list"></i> View All Recommendations
      </a>
    </div>
    @endif
  </div>
</div>