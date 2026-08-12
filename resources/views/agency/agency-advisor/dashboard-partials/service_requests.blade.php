{{-- Service Request Widget --}}

<div class="dashboard-section" id="open-tickets-widget">
    <div class="chart-box mt-2">
        <div class="title">Service Requests</div>
        <div class="scrollable-content">
            <div class="row">
                <div class="col-12">
                    <div class="fund-pool pool-default">
                        <div class="row">
                            <div class="col-xl-2 col-r-15">
                                
                                <label for="fund" class="col-2 col-form-label pr-0">Sponsor</label>
                            </div>
                            <div class="col-xl-6 col-r-15">
                                <select style="font-size: 12px;margin-bottom: 5px;" class="form-control"
                                    onchange="getTicketByCharity(this.value)" id="charity_id">
                                    <option value="0">All Sponsor</option>
                                    @foreach ($sponsors as $sponsor)
										<option value="{{ $sponsor->id }}"
											{{ $sponsor->id == $preferredCharityId2 ? 'selected' : '' }}>
											{{ $sponsor->name }}</option>
									@endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-r-15">
                                <select style="font-size: 12px;" class="form-control"
                                    onchange="generateChart(this.value)" id="chart_id">
                                    @foreach ($charts as $chartKey => $chartVal)
                                        <option style="font-size: 12px;" value="{{ $chartKey }}"
                                            {{ $chartKey == $preferredChartType ? 'selected' : '' }}>
                                            {{ $chartVal }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-r-15">
                                <div class="">
                                    <div id="chartContainer" style="height:180px;width: 100%;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-r-15">
                                <span>Open Tickets: </span>
                            </div>
                            <div class="col-xl-12 col-r-15" id="openTicketsContainer">
                                @include('agency.agency-advisor.dashboard-partials.open_tickets')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
