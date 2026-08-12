<div style="padding: 20px;">
		
	<div class="service-grid" style="display: grid; 
				grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
				gap: 30px; 
				margin-bottom: 30px; 
				align-items: start;">
		
		<div style="width: 100%; max-width: 400px; margin: auto;">
			<canvas id="chartContainer"></canvas>
		</div>

		<!-- Right Side Stats -->
		<div class="request-overview">
			<h3>Ticket Overview</h3>
			<div class="overview-item">
				<span class="overview-label">Total Tickets</span>
				<span class="overview-value">{{ $allTickets['total_tickets'] }}</span>
			</div>
			@foreach($allTickets['status_wise_totals'] as $statusKey => $statusVal)
				<div class="overview-item">
					<span class="overview-label">{{ $statusVal['status_name'] }}</span>
					<span class="overview-value" style="color: #0097b2;">{{ $statusVal['total'] }}</span>
				</div>
			@endforeach
		</div>
	</div>

	<!-- Requests List -->
	<div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
		<h3 style="margin: 0 0 15px 0; color: #333; font-size: 16px;">Recent Tickets</h3>
		<div class="service-list">
			
			@if(count($allTickets['tickets']) > 0)  
				@foreach($allTickets['tickets'] as $ticketKey => $ticket)

				<div class="service-request-item">
					<div class="request-header">
						<span class="request-title">{{ $ticket->title }}</span>
						<div class="request-icons">
								
							<button class="icon-btn blue" title="View Ticket">
								<i onclick="viewTicket({{ $ticket->id }});" title="View Ticket" class="fa fa-eye" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i>
							</button>
							
							<button class="icon-btn green">
								<i onclick="deleteTicket({{ $ticket->id }},'dashboard');" title="Archive Ticket" class="fa fa-archive" aria-hidden="true" style="color:#00758f;cursor:pointer;"></i>
							</button>
							
						</div>
					</div>
					<div class="request-meta">
						<div>
							<strong>Ticket Type:</strong> {{ config('dropdown.category.' . $ticket->category) }} | 
							<strong>Ticket Status:</strong> {{ config('dropdown.status.' . $ticket->status) }} |
							<strong>Priority:</strong> {{ config('dropdown.priority.' . $ticket->priority) }} 
							| <strong>Created At:</strong> {{ \App\Helpers\GnUtils::customDate($ticket->created_at) }}
						</div>
					</div>
				</div>
				@endforeach
			@else
				<div class="col-12" style="display:flex;justify-content:center;align-items:center;">
					<span>No open tickets found!</span>
				</div>
			@endif
			
		</div>
	</div>
</div>