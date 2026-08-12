<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-headset"></i> Service Requests</div>
    <div class="sec-actions">
      <select class="head-sel" id="ticket-chart-type" onchange="switchTicketChart(this.value)">
        <option value="doughnut">Doughnut</option>
        <option value="pie">Pie</option>
      </select>
      <a href="" class="sec-link">View All</a>
    </div>
  </div>
  <div class="sec-body">
    <div class="svc-layout">
      <div class="svc-chart-wrap">
        <canvas id="ticket-chart" aria-label="Service request status distribution"></canvas>
        <div class="svc-chart-title">Status Distribution</div>
      </div>
      <div class="svc-stats-panel">
        <div class="svc-stats-heading">
          <span>Ticket Overview</span>
          <span style="font-size:.72rem;color:var(--muted);font-weight:500">{{ array_sum($ticketStats) }} Total</span>
        </div>
        @php
          $statusConfig = [
            'open'        => ['label' => 'Open',        'color' => '#059669', 'bg' => 'rgba(5,150,105,.1)',  'icon' => 'fa-circle-dot'],
            'in_progress' => ['label' => 'In Progress', 'color' => '#d97706', 'bg' => 'rgba(217,119,6,.1)',  'icon' => 'fa-spinner'],
            'hold'        => ['label' => 'Hold',        'color' => '#9d174d', 'bg' => 'rgba(157,23,77,.1)',  'icon' => 'fa-circle-pause'],
            'closed'      => ['label' => 'Closed',      'color' => '#64748b', 'bg' => 'rgba(100,116,139,.1)','icon' => 'fa-circle-xmark'],
          ];
          $total = max(array_sum($ticketStats), 1);
        @endphp
        <div class="svc-stat-grid">
          @foreach($statusConfig as $key => $cfg)
          @php $count = $ticketStats[$key] ?? 0; @endphp
          <div class="svc-stat-tile" style="border-left:3px solid {{ $cfg['color'] }}">
            <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:2px">
              <i class="fa-solid {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};font-size:.72rem"></i>
              <span style="font-size:.7rem;color:var(--muted);font-weight:600">{{ $cfg['label'] }}</span>
            </div>
            <div style="font-size:1.4rem;font-weight:800;color:{{ $cfg['color'] }};line-height:1;letter-spacing:-.02em">{{ $count }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="ticket-list-header">
      <div class="ticket-list-title">
        <i class="fa-solid fa-list-ul" style="color:var(--teal)"></i>Recent Tickets
      </div>
      <a href="" class="view-all-link" style="font-size:.75rem">View All</a>
    </div>
    @php
      $priClass = ['high' => 'pri-high', 'medium' => 'pri-medium', 'low' => 'pri-low'];
    @endphp
    @forelse(array_slice($tickets, 0, 6) as $ticket)
    @php
      $statusBadge = ['open'=>'b-open','in_progress'=>'b-inprogress','hold'=>'b-hold','closed'=>'b-closed'];
      $statusLabel = ['open'=>'Open','in_progress'=>'In Progress','hold'=>'Hold','closed'=>'Closed'];
    @endphp
    <div class="ticket-item {{ $priClass[$ticket['priority']] ?? 'pri-low' }}">
      <div class="d-flex justify-content-between align-items-start">
        <div class="ticket-title">{{ $ticket['title'] }}</div>
        <div class="d-flex gap-1">
          <button class="icon-btn view" aria-label="View ticket"><i class="fa-regular fa-eye"></i></button>
          <button class="icon-btn arch" aria-label="Archive ticket"><i class="fa-solid fa-box-archive"></i></button>
        </div>
      </div>
      <div class="ticket-meta">
        <span><i class="fa-solid fa-tag me-1"></i>{{ $ticket['category'] }}</span>
        <span class="badge-pill {{ $statusBadge[$ticket['status']] ?? 'b-closed' }}">{{ $statusLabel[$ticket['status']] ?? $ticket['status'] }}</span>
        @if($ticket['priority'] === 'high')
          <span class="ph"><i class="fa-solid fa-circle-exclamation me-1"></i>High</span>
        @elseif($ticket['priority'] === 'medium')
          <span class="pm"><i class="fa-solid fa-circle-minus me-1"></i>Medium</span>
        @else
          <span class="pl"><i class="fa-solid fa-circle-arrow-down me-1"></i>Low</span>
        @endif
        <span><i class="fa-regular fa-calendar me-1"></i>{{ $ticket['date'] }}</span>
      </div>
    </div>
    @empty
    <div class="empty-state"><i class="fa-solid fa-headset"></i>No tickets found.</div>
    @endforelse
  </div>
</div>