<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-chart-pie"></i> Portfolio Allocation</div>
    <div class="sec-actions">
      <div class="chart-toggle">
        <button class="ct-btn active" id="alloc-donut-btn" onclick="switchAlloc('doughnut')">Donut</button>
        <button class="ct-btn" id="alloc-bar-btn" onclick="switchAlloc('bar')">Bar</button>
      </div>
    </div>
  </div>
  <div class="sec-body" style="display:flex;flex-direction:column;align-items:center;gap:.75rem">
    <canvas id="alloc-chart" height="190" aria-label="Portfolio allocation chart"></canvas>
    <div id="alloc-legend" style="width:100%"></div>
  </div>
</div>
