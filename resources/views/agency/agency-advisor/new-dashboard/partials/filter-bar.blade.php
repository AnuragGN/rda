<div class="filter-bar" role="search" aria-label="Dashboard filters">
  <div class="fgrp">
    <label for="dr-sel">Date Range</label>
    <select class="fctl" id="dr-sel" style="min-width:140px">
      <option value="30">Last 30 Days</option>
      <option value="90" selected>Last 90 Days</option>
      <option value="ytd">Year to Date</option>
      <option value="12m">Last 12 Months</option>
      <option value="custom">Custom Range</option>
    </select>
  </div>
  <div class="d-flex gap-2 align-items-end" id="custom-dates" style="display:none!important">
    <div class="fgrp"><label for="date-from">From</label><input type="date" class="fctl" id="date-from"></div>
    <div class="fgrp"><label for="date-to">To</label><input type="date" class="fctl" id="date-to"></div>
  </div>
  <div class="fdivider" aria-hidden="true"></div>
  <div class="fgrp">
    <label for="sponsor-filter">Sponsor</label>
    <select class="fctl" id="sponsor-filter" style="min-width:165px">
      <option value="">All Sponsors</option>
      <option value="cgf">Charitable Gifting Fund</option>
      <option value="bsmhf">Bon Secours Mercy Health Foundation</option>
      <option value="fftc">Foundation For The Carolinas</option>
      <option value="jcfsd">JCF San Diego</option>
      <option value="pbfnc">Provision - Baptist Fdn of NC</option>
      <option value="decho">Dechomai Foundation</option>
    </select>
  </div>
 
  <div class="fgrp">
    <label for="status-filter">Status</label>
    <select class="fctl" id="status-filter">
      <option value="">All Statuses</option>
      <option value="active">Active</option>
      <option value="pending">Pending</option>
      <option value="closed">Closed</option>
    </select>
  </div>
  <div class="fdivider" aria-hidden="true"></div>
  <button class="reset-btn" id="reset-filters">
    <i class="fa-solid fa-xmark"></i> Reset
    <span class="filter-badge" id="filter-badge" style="display:none">0</span>
  </button>
</div>
