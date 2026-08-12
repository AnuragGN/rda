<div class="col-12 col-md-6 dashboard-card">
	
	<div class="dashboard-card-header btn-accent">
		<span>Pending Recommendations</span>
		<div style="display: flex; gap: 15px; align-items: center;">
			<select class="dashboard-select" style="width: 220px; padding: 6px;" onchange="getrecommByCharity(this.value)">
				<option value="0">All Sponsor</option>
				@foreach ($sponsors as $sponsor)
					<option value="{{ $sponsor->id }}"
						{{ $sponsor->id == $preferredCharityId2 ? 'selected' : '' }}>
						{{ $sponsor->name }}
					</option>
				@endforeach
			</select>
		</div>
		<a href="{{route('agency-recommendation')}}" class="view-all-link text-nowrap flex-shrink-0">View All</a>
	</div>
	<div class="dashboard-card-content" id="pending-recommendations-widget">
		@include(
			'agency.agency-advisor.dashboard-partials-new.pending_recommendations',
			['recommendation' => $recommendation]
		)
	</div>
</div>
