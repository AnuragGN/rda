<div class="col-12 col-md-6 dashboard-card">
	
	<div class="dashboard-card-header btn-accent">
		<span>DAF Applications Summary</span>
		<div style="display: flex; gap: 10px; align-items: center;">
			
			<select class="dashboard-select" style="width: 220px; padding: 6px;" onchange="getDAFAccountBySponsor(this.value)" id="sponsor_id">
				<option value="0">All Sponsor</option>
				@foreach ($sponsors as $sponsor)
					<option value="{{ $sponsor->id }}"
						{{ $sponsor->id == $preferredCharityId2 ? 'selected' : '' }}>
						{{ $sponsor->name }}
					</option>
				@endforeach
			</select>
		</div>
		<a href="{{ route('agency-daf-accounts') }}" class="view-all-link text-nowrap flex-shrink-0">View All</a>
	</div>
	<div class="dashboard-card-content" id="daf-account-summary-widget">
		@include(
			'agency.agency-advisor.dashboard-partials-new.daf_accounts',
			['dafAccounts' => $dafAccounts]
		)
	</div>
</div>