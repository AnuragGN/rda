
@if(count($dafAccounts) > 0)
    @foreach($dafAccounts as $key => $val)

      @php
          $donor = is_array($val->donor) ? $val->donor : (json_decode($val->donor ?? '{}', true) ?? []);
          $donorName = trim(($donor['first_name'] ?? '') . ' ' . ($donor['last_name'] ?? '')) ?: 'N/A';
          $fundName = $val->fund_name ?? 'N/A';
          $sponsorName = optional($val->sponsor)->name ?? 'N/A';
     @endphp
    <div class="col-12">
        <div class="fund-pool pool-default">
            <a class="pool-kv js_toggle_pool_values">
                <span class="name">{{ $donorName }}</span>
                <span class="amount">{{ $val['amount'] }}</span>
            </a>
           
            <small><i><b>Sponsor :</b> {{ $sponsorName }}</i></small>,
            <small><i><b>Status :</b> {{ ucfirst($val->status) }}</i></small>,<br>
            <small><i><b>Created On :</b> {{ $val->created_at }}</i></small>

            <a target="_blank" style="float: right;color:#00758f" 
                href="{{ 
					$val->status === 'submitted'
						? route('agency-daf-application-status', ['id' => $val->id])
						: route('agency-daf-account-info', ['id' => $val->id])
				}}">
                <small><i><b>View</b></i></small>
            </a>
        </div>   
    </div>
    @endforeach
@else
    <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
        <span>No DAF Account found!</span>
    </div>
@endif

