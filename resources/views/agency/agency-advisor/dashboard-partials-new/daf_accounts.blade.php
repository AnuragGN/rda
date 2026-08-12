
@if(count($dafAccounts) > 0)
	@foreach($dafAccounts as $key => $val)

		@php
			$donor = is_array($val->donor) ? $val->donor : (json_decode($val->donor ?? '{}', true) ?? []);
			$donorName = trim(($donor['first_name'] ?? '') . ' ' . ($donor['last_name'] ?? '')) ?: 'N/A';
			$fundName = $val->fund_name ?? 'N/A';
			$sponsorName = optional($val->sponsor)->name ?? 'N/A';
		@endphp
		
		<div class="service-request-item">
			<div class="request-header">
				<span class="request-title">{{ $donorName }}</span>
				<div class="request-icons">
					<a target="_blank" href="{{ 
							$val->status === 'submitted'
								? route('agency-daf-application-status', ['id' => $val->id])
								: route('agency-daf-account-info', ['id' => $val->id])
						}}" class="icon-btn blue" title="View">
						<i class="fa fa-eye"></i>
					</a>
				</div>
			</div>
			<div class="request-meta">
				<div><strong>Sponsor:</strong> {{ $sponsorName }}  |  <strong>Status:</strong> {{ ucfirst($val->status) }} |  <strong>Created At:</strong> {{ \App\Helpers\GnUtils::customDate($val->created_at) }}</div>
			</div>
		</div>
		
	@endforeach
@else
	<div class="account-item">
		<div class="account-header">
			<span class="account-name">No DAF Application found!</span>
		</div>
	</div>
@endif
