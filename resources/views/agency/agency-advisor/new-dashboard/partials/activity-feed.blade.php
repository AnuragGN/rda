<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-bolt"></i> Recent Activity</div>
  </div>
  <div class="sec-body">
    @php
      $actIcons = [
        'contribution'    => ['icon' => 'fa-arrow-down-to-line', 'bg' => 'rgba(5,150,105,.12)',  'color' => '#059669'],
        'grant_disbursed' => ['icon' => 'fa-hand-holding-dollar','bg' => 'rgba(8,145,178,.12)',  'color' => '#0891b2'],
        'recommendation'  => ['icon' => 'fa-file-circle-plus',   'bg' => 'rgba(37,99,235,.12)',  'color' => '#2563eb'],
        'ticket_opened'   => ['icon' => 'fa-ticket',             'bg' => 'rgba(220,38,38,.12)',  'color' => '#dc2626'],
        'ticket_resolved' => ['icon' => 'fa-circle-check',       'bg' => 'rgba(5,150,105,.12)',  'color' => '#059669'],
        'balance_updated' => ['icon' => 'fa-chart-line',         'bg' => 'rgba(217,119,6,.12)',  'color' => '#d97706'],
      ];
    @endphp
    @forelse(array_slice($activity, 0, 8) as $event)
    @php $ic = $actIcons[$event['type']] ?? $actIcons['balance_updated']; @endphp
    <div class="act-item">
      <div class="act-icon" style="background:{{ $ic['bg'] }}">
        <i class="fa-solid {{ $ic['icon'] }}" style="color:{{ $ic['color'] }}"></i>
      </div>
      <div class="act-body">
        <div class="act-desc">{{ $event['desc'] }}</div>
        <div class="act-meta">{{ $event['fund'] }} &bull; {{ $event['donor'] }} &bull; {{ $event['ts'] }}</div>
      </div>
    </div>
    @empty
    <div class="empty-state"><i class="fa-solid fa-bolt"></i>No recent activity.</div>
    @endforelse
    @if(count($activity) > 8)
    <button class="load-more-btn" id="load-more-btn" onclick="loadMoreActivity()">
      <i class="fa-solid fa-chevron-down"></i> Load More
    </button>
    @endif
  </div>
</div>