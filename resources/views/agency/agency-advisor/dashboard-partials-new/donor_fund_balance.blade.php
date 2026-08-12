<div class="col-12 col-md-6 dashboard-card">
	<div class="dashboard-card-header btn-accent" style="padding: 14px;">
		<span>Donor Funds Balances</span>
	</div>
	
	<div class="dashboard-card-content">

		@forelse ($charities as $sponsor)

			@php
				$isOpen = $loop->first; // Laravel loop helper
			@endphp

			<div class="sponsor-pool">

				<!-- Sponsor Header -->
				<div class="sponsor-header" onclick="toggleSponsor(this)">
					<div class="sponsor-title">
						<span class="sponsor-toggle {{ $isOpen ? 'expanded' : 'collapsed' }}">
							+
						</span>
						<span>
							{{ $sponsor['name'] ?? 'N/A' }}
						</span>
					</div>

					<div class="sponsor-amount">
						${{ number_format($sponsor['total_balance'] ?? 0, 2) }}
					</div>
				</div>

				<!-- Sponsor Funds -->
				<div class="sponsor-funds" style="display: {{ $isOpen ? 'block' : 'none' }};">

					@forelse ($sponsor['funds'] ?? [] as $fund)

						<div class="fund-row">
							<div class="fund-row-header">
								<span class="fund-row-name">
									{{ $fund['name'] ?? 'N/A' }}
								</span>

								<span class="fund-row-amount">
									${{ number_format($fund['balance'] ?? 0, 2) }}
								</span>
							</div>

							<div class="fund-row-links">

								<a href="{{ route('agency-fund', $fund['fund_id']) }}"
								class="fund-row-link">
									Fund Overview
								</a>

								<a href="{{ route('agency-grant-history', $fund['fund_id']) }}"
								class="fund-row-link">
									Disbursement History
								</a>

								<a href="{{ route('agency-gift-history', $fund['fund_id']) }}"
								class="fund-row-link">
									Contribution History
								</a>

								<a href="{{ route('agency-charity-fund-client', [
										'id' => $sponsor['id'],
										'fund_id' => $fund['fund_id']
									]) }}"
								class="fund-row-link">
									Clients
								</a>

							</div>
						</div>

					@empty
						<div style="padding:12px;">
							No funds associated with this sponsor.
						</div>
					@endforelse

				</div>
			</div>

		@empty
			<div style="padding:15px;">
				No sponsors available.
			</div>
		@endforelse
	</div>
</div>