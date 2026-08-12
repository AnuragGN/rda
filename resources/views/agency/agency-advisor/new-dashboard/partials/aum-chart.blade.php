<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-chart-bar"></i> Balance by Sponsor</div>
    <div class="sec-actions">
      <span class="aum-badge up" id="aum-change-badge">
        <i class="fa-solid fa-dollar-sign"></i>
        ${{ number_format(array_sum(array_column($sponsors, 'aum')) / 1000000, 2) }}M Total
      </span>
    </div>
  </div>
  <div class="sec-body">
    <canvas id="aum-chart" height="100" aria-label="Fund balance by sponsor bar chart"></canvas>
  </div>
</div>
