<div class="sec-card">
  <div class="sec-head">
    <div class="sec-title"><i class="fa-solid fa-building-columns"></i> Institutional Claims</div>
    <div class="sec-actions">
      <button class="sec-btn" onclick="exportClaimsCSV()" aria-label="Export CSV">
        <i class="fa-solid fa-download"></i> Export CSV
      </button>
    </div>
  </div>
  <div class="sec-body" style="overflow-x:auto;padding:0">
    <table class="table table-hover claims-tbl mb-0" aria-label="Institutional claims table">
      <thead>
        <tr>
          <th onclick="sortClaims('name')" style="padding-left:1.1rem">
            Sponsor <i class="fa-solid fa-sort sort-icon" id="si-name"></i>
          </th>
          <th onclick="sortClaims('aum')">
            Total AUM <i class="fa-solid fa-sort sort-icon" id="si-aum"></i>
          </th>
          <th onclick="sortClaims('gifts')">
            Pending Gifts <i class="fa-solid fa-sort sort-icon" id="si-gifts"></i>
          </th>
          <th onclick="sortClaims('grants')">
            Pending Grants <i class="fa-solid fa-sort sort-icon" id="si-grants"></i>
          </th>
        </tr>
      </thead>
      <tbody id="claims-tbody">
        @foreach($sponsors as $sp)
        <tr>
          <td style="padding-left:1.1rem;font-weight:700;color:var(--navy)">{{ $sp['name'] }}</td>
          <td style="font-weight:600">${{ number_format($sp['aum'], 2) }}</td>
          <td><span class="badge-pill b-submitted">${{ number_format($sp['gifts'], 2) }}</span></td>
          <td><span class="badge-pill b-pending">${{ number_format($sp['grants'], 2) }}</span></td>
        </tr>
        @endforeach
      </tbody>
      <tfoot id="claims-tfoot">
        <tr>
          <td style="padding-left:1.1rem">Totals</td>
          <td>${{ number_format(array_sum(array_column($sponsors, 'aum')), 2) }}</td>
          <td>${{ number_format(array_sum(array_column($sponsors, 'gifts')), 2) }}</td>
          <td>${{ number_format(array_sum(array_column($sponsors, 'grants')), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
