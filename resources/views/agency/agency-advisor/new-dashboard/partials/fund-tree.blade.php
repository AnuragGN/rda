<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-wallet"></i> Donor Funds Balances</div>
    <div class="sec-actions">
      <a href="" class="sec-link">View All</a>
      <a href="#" class="sec-btn" id="export-funds-btn"><i class="fa-solid fa-download"></i> CSV</a>
    </div>
  </div>
  <div class="sec-body">
    <div class="search-sort-bar">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" class="search-input" id="fund-search" placeholder="Search funds or donors…">
      </div>
      <select class="sort-sel" id="fund-sort">
        <option value="bal-d">Balance ↓</option>
        <option value="bal-a">Balance ↑</option>
        <option value="name">Name A→Z</option>
      </select>
    </div>
    <div id="fund-tree">
      @foreach($fundsBySponsor as $sponsorId => $group)
      @if(count($group['funds']) > 0)
      @php
        $sp = $group['sponsor'];
        $sf = $group['funds'];
        $total = array_sum(array_column($sf, 'balance'));
        $sid = 'sp-' . $sp['id'];
        $typeBadges = ['daf'=>'b-daf','endowment'=>'b-endowment','scholarship'=>'b-scholarship','other'=>'b-other'];
        $typeLabels = ['daf'=>'DAF','endowment'=>'Endowment','scholarship'=>'Scholarship','other'=>'Other'];
      @endphp
      <div class="sponsor-row" onclick="toggleSponsor('{{ $sid }}')">
        <div>
          <div class="sp-name"><i class="fa-solid fa-building-columns me-2" style="font-size:.78rem"></i>{{ $sp['name'] }}</div>
          <div class="sp-meta">{{ count($sf) }} fund{{ count($sf) !== 1 ? 's' : '' }}</div>
        </div>
        <div class="sp-right">
          <div class="sp-total">${{ number_format($total/1000000, 2) }}M</div>
          <i class="fa-solid fa-chevron-down" id="tog-{{ $sid }}" style="color:var(--teal);font-size:.85rem;transition:transform .25s ease;transform:rotate(-90deg)"></i>
        </div>
      </div>
      <div class="fund-list collapsed" id="{{ $sid }}" style="max-height:0;opacity:0">
        @foreach($sf as $fund)
        <div class="fund-row">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="fund-name">
                <i class="fa-regular fa-folder-open me-1" style="color:var(--teal);font-size:.78rem"></i>
                {{ $fund['name'] }}
                <!-- <span class="badge-pill {{ $typeBadges[$fund['type']] ?? 'b-other' }}" style="font-size:.62rem">{{ $typeLabels[$fund['type']] ?? $fund['type'] }}</span> -->
              </div>
              <!-- <div class="fund-donor">{{ implode(', ', $fund['donors']) }}</div> -->
            </div>
            <div class="fund-bal">${{ number_format($fund['balance']) }}</div>
          </div>
          <div class="fund-links mt-1">
            <a href="#">Overview</a><a href="#">Disbursements</a><a href="#">Contributions</a><a href="#">Clients</a>
          </div>
        </div>
        @endforeach
      </div>
      @endif
      @endforeach
    </div>
  </div>
</div>
