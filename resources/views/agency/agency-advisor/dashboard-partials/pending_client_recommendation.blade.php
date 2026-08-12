{{-- PENDING CLIENT RECOMMENDATION WIDGET --}}

<div class="dashboard-section" id="pending-recommendations-widget">
    <div class="chart-box mt-2" >
        <div class="title"> Pending Client Recommendations </div>
        <div class="row">
            <div class="col-12">
                <div class="fund-pool pool-default">
                    <div class="row">
                        <div class="col-xl-2 col-r-15">
                            <label for="fund" class="col-2 col-form-label pr-0">Sponsor</label>
                        </div>
                        <div class="col-xl-6 col-r-15">
                            <select style="font-size: 12px;margin-bottom: 5px;" class="form-control"
                                onchange="getrecommByCharity(this.value)" id="charity_id">
                                <option value="0">All Sponsor</option>
                                @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}"
                                        {{ $sponsor->id == $preferredCharityId2 ? 'selected' : '' }}>
                                        {{ $sponsor->name }}</option>
                                @endforeach
                            </select>
                        </div>
						<div class="col-xl-4">
							<a style="float: right;color:#00758f" href="{{route('agency-recommendation')}}" title="View All Pending Recommendations"><small><i><b>View All</b></i></small></a>
                        </div>
                    </div>
                    <div class="scrollable-content" style="max-height: 295px;!important;">
                        <div class="row">
                            @include(
                                'agency.agency-advisor.dashboard-partials.pending_recommendations',
                                ['recommendation' => $recommendation]
                            )
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
