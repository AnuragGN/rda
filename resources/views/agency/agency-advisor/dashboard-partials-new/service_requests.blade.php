<div class="col-12 col-md-12 dashboard-card">
	<div class="dashboard-card-header btn-accent">
		<span>Service Requests</span>
		<div style="display: flex; gap: 15px; align-items: center;">
			
			<select class="dashboard-select" style="padding: 6px;" onchange="getTicketBySponsor(this.value)" id="sponsor_id">
				<option value="0">All Sponsor</option>
				@foreach ($sponsors as $sponsor)
					<option value="{{ $sponsor->id }}"
						{{ $sponsor->id == $preferredCharityId2 ? 'selected' : '' }}>
						{{ $sponsor->name }}
					</option>
				@endforeach
			</select>
			
			<select style="padding: 6px;" class="dashboard-select" onchange="generateTicketChart(this.value)" id="chart_id">
				@foreach ($charts as $chartKey => $chartVal)
					<option style="font-size: 12px;" value="{{ $chartKey }}"
						{{ $chartKey == $preferredChartType ? 'selected' : '' }}>
						{{ $chartVal }}</option>
				@endforeach
			</select>
			<a href="{{ route('agency-ticket') }}" class="view-all-link text-nowrap flex-shrink-0">View All</a>
		</div>
	</div>
	<div class="dashboard-card-content" id="openTicketsContainer">
		@include(
			'agency.agency-advisor.dashboard-partials-new.open_tickets',
			['allTickets ' => $allTickets ]
		)
	</div>
</div>