<div class="col-12 col-md-6 dashboard-card">
	<div class="dashboard-card-header btn-accent" style="padding: 14px;">
		<span>Institutional Claims</span>
	</div>
	<div class="dashboard-card-content">
		<table class="claims-table">
			<thead>
				<tr>
					<th>Sponsor</th>
					<th>Total AUM</th>
					<th>Pending Gifts</th>
					<th>Pending Grants</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($charities as $charity)
					<tr>
						<td>
							@php
								// Define the logo path variable
								$logoPath = 'ma/uploads/logos/' . $charity['id'] . '.png';
								$fullLogoPath = public_path($logoPath);
							@endphp
							
							@if (file_exists($fullLogoPath))
								<img src="{{ asset($logoPath) }}" alt="{{ $charity['name'] }}" style="width: 50%; height: 50px; ">
							@else
								{{ $charity['name'] }}
							@endif
						</td>
						<td>${{ number_format($charity['total_balance'] ?? 0, 2) }}
						</td>
						<td></td>
						<td>${{ number_format($charity['pending_grants_balance'] ?? 0, 2) }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>