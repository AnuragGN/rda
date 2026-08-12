@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), \App\Helpers\GnUtils::isDonorSession() ? ['container' => 'none'] : [])

@section('content')

    @include('common.page-header', ['pageTitle' => 'Preferences', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper2 form-last2">
                <div class="row profile-view">
                    <div class="col-xl-9">
                        <div class="card gn-shadow profile-info">
                            <div class="header">
                                <div class="collapsible-child-visible">
                                    Set Your Preferences
                                </div>
                            </div> 
                            <div class="body">
                                <form action="{{ route('user.preferences.save') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="form-group row">
                                                <label for="charity_id" class="col-sm-3 col-form-label text-right pr-0">Sponsor </label>
                                                <div class="col-sm-6">
                                                    <select id="charity_id" class="form-control" name="charity">
                                                        <option value="" disabled selected>Select Sponsor</option>
                                                        @foreach($sponsors as $sponsor)
                                                            <option value="{{ $sponsor->id }}" {{ $sponsor->id == $selectedCharityId ? 'selected' : '' }}>{{ $sponsor->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="chart" class="col-sm-3 col-form-label text-right pr-0">Chart </label>
                                                <div class="col-sm-6">
                                                    <select id="chart" class="form-control" name="chart">
                                                        <option value="" disabled selected>Click to select Sponsor</option>
                                                        @foreach($charts as $chartKey => $chartVal)
                                                            <option value="{{ $chartKey }}" {{ $chartKey == $selectedChartType ? 'selected' : '' }}>{{ $chartVal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                
                                            <div class="form-group row">
                                                <label for="widget_order" class="col-sm-3 col-form-label text-right pr-0">Widget Order</label>
                                                <div class="col-sm-6">
                                                    <ul id="sortable-widgets" class="list-group">
                                                        @foreach($widgetOrder as $widget)
                                                            @switch($widget)
                                                                @case('donor_fund_balance')
                                                                    <li class="list-group-item bg-primary text-white" data-widget="donor_fund_balance">
                                                                        <i class="fas fa-dollar-sign"></i>Donor Fund Balance
                                                                    </li>
                                                                    @break
                                                                @case('service_requests')
                                                                    <li class="list-group-item bg-success text-white" data-widget="service_requests">
                                                                        <i class="fas fa-tasks"></i> Service Requests
                                                                    </li>
                                                                    @break
                                                                @case('pending_client_recommendation')
                                                                    <li class="list-group-item bg-warning text-dark" data-widget="pending_client_recommendation">
                                                                        <i class="fas fa-clock"></i> Pending Client Recommendations
                                                                    </li>
                                                                    @break
                                                                @case('institutional_client')
                                                                    <li class="list-group-item bg-info text-white" data-widget="institutional_client">
                                                                        <i class="fas fa-building"></i> Institutional Clients
                                                                    </li>
                                                                    @break
                                                                @case('daf_account_summary')
                                                                    <li class="list-group-item bg-danger text-white" data-widget="daf_account_summary">
                                                                        <i class="fas fa-university"></i> DAF Account Summary
                                                                    </li>
                                                                    @break
                                                            @endswitch
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="widget_order" id="widget_order" value="">
                                
                                            <hr>
                                            <div class="form-group row">
                                                <div class="offset-sm-3 col-sm-5 col-md-4">
                                                    <input name="save" id="id_save_btn" class="btn btn-accent w100" type="submit" value="Update">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            $("#sortable-widgets").sortable({
                update: function(event, ui) {
                    var order = $(this).sortable('toArray', {attribute: 'data-widget'});
                    $('#widget_order').val(order.join(','));
                }
            });
        });
    </script>

@endsection
