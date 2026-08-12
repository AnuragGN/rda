<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-filter"></i> Recommendation Pipeline</div>
  </div>
  <div class="sec-body">
    @foreach($pipeline as $key => $stage)
    <div class="pipeline-stage" id="stage-{{ $key }}" onclick="togglePipelineStage('ps-{{ $key }}')">
      <div class="ps-header">
        <div class="ps-name">
          <span style="width:10px;height:10px;border-radius:50%;background:{{ $stage['color'] }};display:inline-block"></span>
          <span style="font-weight:700">{{ $stage['label'] }}</span>
          <span class="badge-pill" style="background:{{ $stage['color'] }}20;color:{{ $stage['color'] }};font-size:.65rem">{{ $stage['count'] }}</span>
        </div>
        <div class="ps-meta" style="font-weight:700;color:var(--teal)">${{ number_format($stage['total']) }}</div>
        <i class="fa-solid fa-chevron-down ps-chevron ms-2"></i>
      </div>
      <div class="ps-track">
        <div class="ps-fill" style="width:{{ $stage['pct'] }}%;background:{{ $stage['color'] }}"></div>
      </div>
      <div class="ps-grants" id="ps-{{ $key }}">
        @forelse($stage['items'] as $grant)
        <div class="ps-grant-item">
          <span>{{ $grant['org'] }}</span>
          <span style="font-weight:600;color:var(--text)">${{ number_format($grant['amount']) }}</span>
        </div>
        @empty
        <div style="font-size:.75rem;color:var(--muted);padding:.2rem 0">No grants</div>
        @endforelse
      </div>
    </div>
    @endforeach
    <div class="pipeline-summary">
      <span style="font-weight:600;color:var(--navy)">
        <i class="fa-solid fa-clock me-1" style="color:var(--teal)"></i>Total Pending
      </span>
      <span style="font-weight:800;color:var(--teal)">${{ number_format($pendingTotal) }}</span>
    </div>
  </div>
</div>
