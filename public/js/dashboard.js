// AdvisorHub Dashboard JS
// Data is injected by Laravel as window.DASHBOARD_DATA

(function () {
  'use strict';

  // ── Helpers ────────────────────────────────────────────────────────────────
  function fmtCurrency(n) {
    if (n >= 1000000) return '$' + (n / 1000000).toFixed(2) + 'M';
    if (n >= 1000)    return '$' + (n / 1000).toFixed(0) + 'K';
    return '$' + n.toLocaleString();
  }
  function fmtPct(n) { return (n >= 0 ? '+' : '') + n.toFixed(1) + '%'; }
  function debounce(fn, ms) {
    var t; return function () { var a = arguments; clearTimeout(t); t = setTimeout(function () { fn.apply(null, a); }, ms); };
  }

  // ── State ──────────────────────────────────────────────────────────────────
  var charts = { aum: null, alloc: null, perf: null, ticket: null };
  var allocType = 'doughnut';
  var activeSponsorId = null;
  var activityPage = 1;
  var claimsSort = { col: 'aum', dir: 'desc' };

  // ── Data (from Laravel) ────────────────────────────────────────────────────
  var D               = window.DASHBOARD_DATA || {};
  var SPONSOR_BALANCES = D.sponsorBalances || [];
  var PERF_DATA = [];
  var FUNDS     = [];

  // Sponsors from claims table rows (fallback)
  var SPONSORS = window.SPONSORS_DATA || [];

  // ── Palette ────────────────────────────────────────────────────────────────
  var PALETTE = ['#0891b2','#059669','#d97706','#7c3aed','#dc2626','#2563eb'];
  var STAGE_COLORS = { submitted:'#2563eb', under_review:'#d97706', approved:'#059669', disbursed:'#0891b2', rejected:'#dc2626' };

  // ── AUM TREND CHART ────────────────────────────────────────────────────────
  function renderAumChart() {
    var canvas = document.getElementById('aum-chart');
    if (!canvas || !SPONSOR_BALANCES.length) return;
    if (charts.aum) { charts.aum.destroy(); charts.aum = null; }
    var labels = SPONSOR_BALANCES.map(function(s) { return s.label; });
    var values = SPONSOR_BALANCES.map(function(s) { return s.balance; });
    var colors = ['#0891b2','#059669','#7c3aed','#d97706','#dc2626','#2563eb'];
    charts.aum = new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{ label: 'Fund Balance', data: values, backgroundColor: colors.slice(0, labels.length).map(function(c){return c+'cc';}), borderColor: colors.slice(0, labels.length), borderWidth: 1.5, borderRadius: 6, borderSkipped: false }]
      },
      options: {
        indexAxis: 'y', responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(c) { return ' ' + fmtCurrency(c.parsed.x); } } } },
        scales: { x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: function(v) { return fmtCurrency(v); } } }, y: { grid: { display: false }, ticks: { font: { size: 10 } } } }
      }
    });
  }
  // ── PORTFOLIO ALLOCATION CHART ─────────────────────────────────────────────
  function renderAllocChart() {
    var canvas = document.getElementById('alloc-chart');
    if (!canvas || !FUNDS.length) return;
    if (charts.alloc) { charts.alloc.destroy(); charts.alloc = null; }
    var ctx = canvas.getContext('2d');

    var groups = {};
    FUNDS.forEach(function (f) { groups[f.type] = (groups[f.type] || 0) + f.balance; });
    var labels = Object.keys(groups).map(function (k) { return k.charAt(0).toUpperCase() + k.slice(1); });
    var values = Object.values(groups);
    var total  = values.reduce(function (a, b) { return a + b; }, 0);
    var colors = PALETTE.slice(0, labels.length);

    if (allocType === 'doughnut') {
      charts.alloc = new Chart(ctx, {
        type: 'doughnut',
        data: { labels: labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverOffset: 8 }] },
        options: {
          responsive: true, maintainAspectRatio: true, cutout: '68%',
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: function (c) { return ' ' + c.label + ': ' + fmtCurrency(c.parsed) + ' (' + ((c.parsed / total) * 100).toFixed(1) + '%)'; } } }
          }
        }
      });
    } else {
      charts.alloc = new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: [{ data: values, backgroundColor: colors, borderRadius: 6, borderSkipped: false }] },
        options: {
          indexAxis: 'y', responsive: true, maintainAspectRatio: true,
          plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return ' ' + fmtCurrency(c.parsed.x); } } } },
          scales: { x: { grid: { color: '#f1f5f9' }, ticks: { callback: function (v) { return fmtCurrency(v); }, font: { size: 10 } } }, y: { grid: { display: false }, ticks: { font: { size: 11 } } } }
        }
      });
    }

    var leg = document.getElementById('alloc-legend');
    if (leg) {
      leg.innerHTML = labels.map(function (l, i) {
        return '<div style="display:flex;align-items:center;justify-content:space-between;padding:.2rem 0;font-size:.75rem">' +
          '<span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:' + colors[i] + ';margin-right:6px"></span>' + l + '</span>' +
          '<span style="font-weight:700;color:var(--text)">' + ((values[i] / total) * 100).toFixed(1) + '%</span></div>';
      }).join('');
    }
  }

  window.switchAlloc = function (type) {
    allocType = type;
    var db = document.getElementById('alloc-donut-btn');
    var bb = document.getElementById('alloc-bar-btn');
    if (db) { db.classList.toggle('active', type === 'doughnut'); db.setAttribute('aria-pressed', type === 'doughnut'); }
    if (bb) { bb.classList.toggle('active', type === 'bar');      bb.setAttribute('aria-pressed', type === 'bar'); }
    renderAllocChart();
  };

  // ── PERFORMANCE CHART ──────────────────────────────────────────────────────
  window.renderPerfChart = function (fundId) {
    var canvas = document.getElementById('perf-chart');
    if (!canvas || !PERF_DATA.length) return;
    if (charts.perf) { charts.perf.destroy(); charts.perf = null; }
    var key = fundId || 'all';
    charts.perf = new Chart(canvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: PERF_DATA.map(function (d) { return d.month; }),
        datasets: [
          { label: 'Fund Return', data: PERF_DATA.map(function (d) { return d[key] !== undefined ? d[key] : d.all; }), borderColor: '#0891b2', borderWidth: 2.5, tension: 0.4, pointBackgroundColor: '#0891b2', pointRadius: 3, pointHoverRadius: 6, fill: { target: 1, above: 'rgba(8,145,178,0.08)', below: 'rgba(220,38,38,0.08)' } },
          { label: 'S&P 500',    data: PERF_DATA.map(function (d) { return d.benchmark; }), borderColor: '#94a3b8', borderWidth: 1.5, borderDash: [5, 3], tension: 0.4, pointRadius: 2, pointHoverRadius: 5, fill: false }
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: true,
        plugins: {
          legend: { display: true, position: 'top', labels: { font: { size: 11, family: 'Inter' }, boxWidth: 12, padding: 12 } },
          tooltip: { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + fmtPct(c.parsed.y); } } }
        },
        scales: {
          x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
          y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: function (v) { return fmtPct(v); } } }
        }
      }
    });
  };

  // ── TICKET CHART ───────────────────────────────────────────────────────────
  var centerLabelPlugin = {
    id: 'centerLabel',
    afterDraw: function (chart) {
      if (chart.config.type !== 'doughnut') return;
      var ctx  = chart.ctx;
      var cx   = (chart.chartArea.left + chart.chartArea.right)  / 2;
      var cy   = (chart.chartArea.top  + chart.chartArea.bottom) / 2;
      var total = chart.data.datasets[0].data.reduce(function (a, b) { return a + b; }, 0);
      ctx.save();
      ctx.textAlign    = 'center';
      ctx.textBaseline = 'middle';
      ctx.font         = 'bold 1.4rem Inter, sans-serif';
      ctx.fillStyle    = '#1e3a5f';
      ctx.fillText(total, cx, cy - 8);
      ctx.font         = '500 .65rem Inter, sans-serif';
      ctx.fillStyle    = '#64748b';
      ctx.fillText('Total', cx, cy + 10);
      ctx.restore();
    }
  };
  Chart.register(centerLabelPlugin);

  function renderTicketChart(type) {
    var canvas = document.getElementById('ticket-chart');
    if (!canvas) return;
    if (charts.ticket) { charts.ticket.destroy(); charts.ticket = null; }
    var stats = D.ticketStats || {};
    var data  = [stats.open || 0, stats.in_progress || 0, stats.hold || 0, stats.closed || 0];
    charts.ticket = new Chart(canvas.getContext('2d'), {
      type: type || 'doughnut',
      data: {
        labels: ['Open', 'In Progress', 'Hold', 'Closed'],
        datasets: [{ data: data, backgroundColor: ['#059669','#d97706','#9d174d','#64748b'], borderWidth: 2, borderColor: '#fff', hoverOffset: 8 }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        cutout: type === 'pie' ? '0%' : '65%',
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: function (c) { var t = c.dataset.data.reduce(function (a, b) { return a + b; }, 0); return ' ' + c.label + ': ' + c.parsed + ' (' + Math.round(c.parsed / t * 100) + '%)'; } } }
        }
      }
    });
  }

  window.switchTicketChart = function (type) { renderTicketChart(type); };

  // ── GRANT PIPELINE ─────────────────────────────────────────────────────────
  window.togglePipelineStage = function (id) {
    var panel = document.getElementById(id);
    if (!panel) return;
    var isOpen = panel.classList.contains('open');
    var stage  = panel.closest ? panel.closest('.pipeline-stage') : panel.parentElement;
    if (isOpen) {
      panel.classList.remove('open');
      if (stage) stage.classList.remove('expanded');
    } else {
      panel.classList.add('open');
      if (stage) stage.classList.add('expanded');
    }
  };

  // ── FUND TREE ACCORDION ────────────────────────────────────────────────────
  window.toggleSponsor = function (clickedId) {
    document.querySelectorAll('.fund-list').forEach(function (panel) {
      var sid = panel.id;
      var tog = document.getElementById('tog-' + sid);
      if (sid === clickedId) {
        var isCollapsed = panel.classList.contains('collapsed');
        if (isCollapsed) {
          panel.classList.remove('collapsed'); panel.style.maxHeight = '600px'; panel.style.opacity = '1';
          if (tog) tog.style.transform = 'rotate(0deg)';
        } else {
          panel.classList.add('collapsed'); panel.style.maxHeight = '0'; panel.style.opacity = '0';
          if (tog) tog.style.transform = 'rotate(-90deg)';
        }
      } else {
        panel.classList.add('collapsed'); panel.style.maxHeight = '0'; panel.style.opacity = '0';
        if (tog) tog.style.transform = 'rotate(-90deg)';
      }
    });
  };

  // ── FILTER BAR ─────────────────────────────────────────────────────────────
  function updateFilterBadge() {
    var defaults = { 'dr-sel': '90', 'sponsor-filter': '', 'type-filter': '', 'status-filter': '' };
    var count = 0;
    Object.keys(defaults).forEach(function (id) {
      var el = document.getElementById(id);
      if (el && el.value !== defaults[id]) count++;
    });
    var badge = document.getElementById('filter-badge');
    if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'inline-flex' : 'none'; }
  }

  window.resetFilters = function () {
    ['dr-sel','sponsor-filter','type-filter','status-filter'].forEach(function (id) {
      var el = document.getElementById(id); if (el) el.value = id === 'dr-sel' ? '90' : '';
    });
    var cd = document.getElementById('custom-dates'); if (cd) cd.style.display = 'none';
    updateFilterBadge();
  };

  // ── REFRESH ────────────────────────────────────────────────────────────────
  function doRefresh() {
    var btn  = document.getElementById('refresh-btn');
    var icon = document.getElementById('refresh-icon');
    if (!btn) return;
    btn.disabled = true;
    if (icon) icon.classList.add('spinning');
    setTimeout(function () {
      var now = new Date();
      var ts  = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' +
                now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
      var luv = document.getElementById('last-updated-val'); if (luv) luv.textContent = ts;
      btn.disabled = false;
      if (icon) icon.classList.remove('spinning');
      renderAumChart(); renderTicketChart('doughnut');
    }, 800);
  }

  // ── SIDEBAR ────────────────────────────────────────────────────────────────
  function initSidebar() {
    var toggle  = document.getElementById('sidebar-toggle');
    var sidebar = document.getElementById('app-sidebar');
    var main    = document.getElementById('app-main');
    var overlay = document.getElementById('app-overlay');
    if (!toggle || !sidebar) return;
    toggle.addEventListener('click', function () {
      if (window.innerWidth < 768) {
        sidebar.classList.toggle('mobile-open');
        if (overlay) overlay.classList.toggle('show');
      } else {
        sidebar.classList.toggle('collapsed');
        if (main) main.classList.toggle('sidebar-collapsed');
      }
    });
    if (overlay) overlay.addEventListener('click', function () {
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('show');
    });
  }

  // ── CSV EXPORT ─────────────────────────────────────────────────────────────
  function exportCSV(rows, filename) {
    var csv  = rows.map(function (r) { return r.map(function (c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(','); }).join('\n');
    var blob = new Blob([csv], { type: 'text/csv' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a'); a.href = url; a.download = filename; a.click();
    URL.revokeObjectURL(url);
  }

  window.exportFundsCSV = function () {
    var rows = [['Fund Name', 'Donor', 'Type', 'Balance']];
    FUNDS.forEach(function (f) { rows.push([f.name, (f.donors || []).join(', '), f.type, f.balance]); });
    exportCSV(rows, 'donor-funds-' + new Date().toISOString().slice(0, 10) + '.csv');
  };

  window.exportClaimsCSV = function () {
    var rows = [['Sponsor', 'Total AUM', 'Active Funds', 'Pending Gifts', 'Pending Grants', 'YTD Disbursements']];
    (window.SPONSORS_DATA || []).forEach(function (s) { rows.push([s.name, s.aum, s.funds, s.gifts, s.grants, s.ytd]); });
    exportCSV(rows, 'institutional-claims-' + new Date().toISOString().slice(0, 10) + '.csv');
  };

  window.sortClaims = function (col) {
    if (claimsSort.col === col) { claimsSort.dir = claimsSort.dir === 'asc' ? 'desc' : 'asc'; }
    else { claimsSort.col = col; claimsSort.dir = col === 'name' ? 'asc' : 'desc'; }
    // Update sort icons
    ['name','aum','funds','gifts','grants','ytd'].forEach(function (c) {
      var el = document.getElementById('si-' + c); if (!el) return;
      el.className = 'fa-solid sort-icon ' + (c === col ? (claimsSort.dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') + ' active' : 'fa-sort');
    });
  };

  window.loadMoreActivity = function () {
    activityPage++;
    var btn = document.getElementById('load-more-btn');
    if (btn) btn.style.display = 'none'; // server-rendered, just hide
  };

  // ── INIT ───────────────────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    initSidebar();

    // Refresh button
    var rfBtn = document.getElementById('refresh-btn');
    if (rfBtn) rfBtn.addEventListener('click', doRefresh);

    // Filter bar
    var drSel = document.getElementById('dr-sel');
    if (drSel) drSel.addEventListener('change', function () {
      var cd = document.getElementById('custom-dates');
      if (cd) cd.style.display = drSel.value === 'custom' ? 'flex' : 'none';
      updateFilterBadge();
    });
    ['sponsor-filter','type-filter','status-filter'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.addEventListener('change', updateFilterBadge);
    });
    var rstBtn = document.getElementById('reset-filters');
    if (rstBtn) rstBtn.addEventListener('click', window.resetFilters);

    // Render all charts
    renderAumChart();
    renderTicketChart('doughnut');
  });

}());

