@forelse ($recommendation as $val)

    <div class="recommendation-item">

        <div class="recommendation-header">
            <span class="recommendation-name">
                {{ $val['org_name'] ?? 'N/A' }}
            </span>

			<span class="recommendation-amount">{{ $val['amount'] }}</span>
        </div>

        <div class="recommendation-meta">

            <span class="badge badge-warning">
                Recommended by {{ $val['contact_name'] ?? 'N/A' }}
            </span>
            <br>

            <strong>Fund</strong>:
            {{ $val['fund_name'] ?? 'N/A' }}

            |

            <strong>Status</strong>:
            @if (($val['status'] ?? null) === 'N')
                Approval Pending
            @else
                Approved on {{ $val['approved_date'] ?? 'N/A' }}
            @endif

            |

            <strong>Created At</strong>:
            {{ $val['date_submitted'] ?? 'N/A' }}

            <span style="float: right;" class="badge badge-error">

                @if (!empty($val['ticket']))
                    <a target="_blank"
                       style="float: right;color:#721c53;"
                       title="View Ticket"
                       href="{{ route('agency-service-ticket-view', [
                            'ticket_id' => $val['ticket']
                       ]) }}">
                        View Ticket
                    </a>
                @else
                    <a target="_blank"
                       style="float: right;color:#721c53;"
                       title="Create Ticket"
                       href="{{ route('agency-service-ticket-create', [
                            'recommendation_id' => $val['fund_recommendation_id'] ?? null
                       ]) }}">
                        Create Ticket
                    </a>
                @endif

            </span>

        </div>

    </div>

@empty

    <div class="account-item">
        <div class="account-header">
            <span class="account-name">
                No pending recommendations found for the selected sponsor!
            </span>
        </div>
    </div>

@endforelse