<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-chart-area"></i> Fund Performance vs S&amp;P 500</div>
    <div class="sec-actions">
      <select class="head-sel" id="perf-fund-sel" onchange="renderPerfChart(this.value)">
        <option value="all">All Funds (Blended)</option>
        <option value="f1">Smith Family Fund</option>
        <option value="f2">Green Future Endowment</option>
        <option value="f3">Johnson Charitable Fund</option>
        <option value="f4">Heritage Education Fund</option>
        <option value="f5">Chen Family Foundation</option>
      </select>
    </div>
  </div>
  <div class="sec-body">
    <canvas id="perf-chart" height="100" aria-label="Fund performance vs S&P 500"></canvas>
  </div>
</div>
