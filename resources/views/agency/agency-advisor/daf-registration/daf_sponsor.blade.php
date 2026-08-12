<?php
    $sponsorName = null;
    $sponsorShortName = null;

    if (!empty($id)) {
        // Get DAF account
        $acct = \App\Models\DAFAccount::getDAFAccount($id);

        if ($acct && !empty($acct->sponsor_id)) {
            // Get sponsor by ID
            $sp = \App\Models\FaSponser::getDafSponsorById($acct->sponsor_id);

            if ($sp) {
                // Set sponsor name (fallback to sponsor_name if name missing)
                $sponsorName = $sp->name ?? $sp->sponsor_name ?? null;

                // Set sponsor initials
                $sponsorShortName = $sp->sponsor_id ?? null; // assuming 'initials' column exists
            }
        }
    }

    $status = ucfirst(strtolower($acct->sponsor_sync ?? 'Pending'));

    $statusConfig = [
        'Pending' => [
            'class' => 'badge-warning',
            'icon'  => 'fa-clock'
        ],
        'Approved' => [
            'class' => 'badge-info',
            'icon'  => 'fa-thumbs-up'
        ],
        'Completed' => [
            'class' => 'badge-success',
            'icon'  => 'fa-check-circle'
        ],
        'Rejected' => [
            'class' => 'badge-danger',
            'icon'  => 'fa-times-circle'
        ],
    ];

    $badgeClass = $statusConfig[$status]['class'] ?? 'badge-secondary';
    $iconClass  = $statusConfig[$status]['icon'] ?? 'fa-info-circle';

    ?>

    @if($sponsorName)
        <div class="container pageTop">
            <div class="mb-3">
                <div class="card border-0 shadow-sm p-2 position-relative">
                    
                    <!-- Sync Status (Top Right) -->
                   <span class="badge {{ $badgeClass }} position-absolute d-flex align-items-center"
                        style="top:10px; right:12px; gap:6px; padding:6px 10px; font-size:12px;">
                        <i class="fa {{ $iconClass }}"></i>
                        <span class="font-weight-600">
                            Sponsor Sync
                        </span>
                        <span class="opacity-775"> : </span>
                        <span>{{ $status }}</span>
                    </span>

                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 d-flex align-items-center justify-content-center"
                            style="width:70px;height:56px;border-radius:8px;
                            background:linear-gradient(135deg,#6f42c1,#007bff);
                            color:#fff;font-weight:700;font-size:18px">
                            {{ $sponsorShortName }}
                        </div>

                        <div>
                            <div class="h5 mb-0">{{ $sponsorName }}</div>
                            <small class="text-muted">DAF Sponsor</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif