@extends('agency.layouts.main')
@section('content')
@include('common.page-header', ['pageTitle' => 'Grant Recommendations', 'hcXlWidth' => 12])
<style>
.canvasjs-chart-credit{
    display: none;
}
</style>
    <div class="container history-container">   
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-7 col-r-15">
                    <div class="page-title mt-2">
                        <div class="container-fluid">  
                            <div class="row">
                                <div class="col-xl-7 col-sm-12"><h3>Recommendation List</h3></div> 
                                <div class="col-xl-5 col-sm-12 text-right">
                                    <a href="javascript:void(0);" style="background-color: #e6f4f7;" 
                                       onclick="sageCollapsible(this)" data-child-id="statement-filter"
                                       class="btn btn-light btn-sm shadowed mr-2 ml-2">
                                        Filter <i class="fas fa-filter"></i></a> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('agency-recommendation') }}" method="GET" accept-charset="UTF-8" id="form-history-filter">
                        <div class="row" id="statement-filter" >
                            <div class="col-12">
                                <div class="filter-row">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Fund</b></small></label>
                                            </div>
                                            <div style="display: flex">
                                                <select class="form-control" id="fund_id" name="fund_id">
                                                    <option value="0">All Fund</option>
                                                    @foreach ($contactFunds as $fund => $val)
                                                        <option value="{{ $fund }}" {{ request('fund_id') == $fund ? 'selected' : '' }}>{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Date Range</b></small></label>
                                            </div>
                                            <div style="display: flex">
                                                <select class="form-control" id="grant_date_range" name="grant_date_range" 
                                                onchange="getFilter();">
                                                        <option value="Last 30 Days" {{ request('grant_date_range') == 'Last 30 Days' ? 'selected' : '' }}>Last 30 Days</option>
                                                        <option value="Last Month" {{ request('grant_date_range') == 'Last Month' ? 'selected' : '' }}>Last Month</option>
                                                        <option value="Last 3 Months" {{ request('grant_date_range') == 'Last 3 Months' ? 'selected' : '' }}>Last 3 Months</option>
                                                        <option value="Last 6 Months" {{ request('grant_date_range') == 'Last 6 Months' ? 'selected' : '' }}>Last 6 Months</option>
                                                        <option value="Last 1 Year" {{ request('grant_date_range') == 'Last 1 Year' ? 'selected' : '' }}>Last 1 Year</option>
                                                        <option value="Custom Date" {{ request('grant_date_range') == 'Custom Date' ? 'selected' : '' }}>Custom Date</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="startDateDiv" style="display:none; ">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Select Period</b></small></label>
                                            </div>
                                            <div style="display: flex" class="mb-2">
                                                <input type="text" id="id-date-range" name="dateRange" class="form-control"  value="12/01/2023 - 01/31/2024" />
                                                <input id="start_date" name="start_date" type="hidden" value="1">
                                                <input id="end_date" name="end_date" type="hidden" value="3">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-accent w-100">
                                                Search
                                            </button>
                                        </div>
                                        @php
                                            $hasFilter = request()->filled('fund_id') ||
                                            request()->filled('grant_date_range') ||
                                            request()->filled('start_date') ||
                                            request()->filled('end_date') ;
                                        @endphp
                                        <div class="col-xl-3 col-md-3">
                                            <a href="{{ route('agency-recommendation') }}"
                                            id="clear_search"
                                            style="{{ $hasFilter ? '' : 'display:none;' }}">
                                                Clear search
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                     @if($recommendation && count($recommendation))
                        @forelse($recommendation as $i => $recom)
                            <div class="col-12">
                                <div class="fund-pool pool-default">
                                    <a class="pool-kv js_toggle_pool_values" 
                                    title="{{ $recom['fund_name'] }}">
                                        <span class="name"> {{ $recom['org_name'] }}</span>
                                        <span class="amount"> {{ $recom['amount'] }} </span>
                                    </a>
                                    <span class="badge badge-warning">Recommended by {{ $recom['contact_name'] }}</span><br>
                                    <small><i><b>Fund:</b> {{ $recom['fund_name'] }}</i></small> | 
                                    <small><i><b>Status:</b> 
                                    @if($recom['status'] == 'N')
                                        Approval Pending
                                    @else
                                        Approved on {{ $recom['approved_date'] }}
                                    @endif
                                    </i></small> | 
                                    <small><i><b>Created On:</b> {{ $recom['date_submitted'] }}</i></small>

                                    @if ($recom['ticket'] != '')
                                        <a target="_blank" style="float: right;color:#00758f" 
                                            href="{{ route('agency-service-ticket-view',['ticket_id' => $recom['ticket']]) }}">
                                            <small><i><b>View Ticket</b></i></small>
                                        </a>
                                    @else
                                        <a target="_blank" style="float: right;color:red;" 
                                            href="{{ route('agency-service-ticket-create', ['recommendation_id' => $recom['fund_recommendation_id']]) }}">
                                            <small><i><b>Create Ticket</b></i></small>
                                        </a>
                                    @endif
                                </div>   
                            </div>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                No Recommendation found.
                            </td>
                        </tr>
                    @endif
                    <br>
                </div>
                <div class="col-xl-5 col-r-15">
                    <h3 class="page-subtitle mt-3">
                       Graph Distribution
                    </h3>

                    <div style="display: flex">
                        <div class="mb-2 mr-sm-2" style="width: 100%">
                            <select class="form-control" onchange="generateRecommendationChart(this.value);" id="chart_id">
                                @foreach($charts as $chartKey => $chartVal)
                                    <option style="font-size: 12px;" value="{{ $chartKey }}" 
                                     > {{ $chartVal }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-12">
                            <canvas id="chartContainer"></canvas>
                        </div> 
                    </div>
                </div>  
            </div>
        </div>
    </div>
@include('agency.agency-advisor.common-script')   
<script>

    function getFilter() 
    {
        $("#startDateDiv").hide();
        $("#endDateDiv").hide();
        $("#searchDiv").hide();

        var grant_date_range = $("#grant_date_range").val();
        if(grant_date_range == 'Custom Date') 
        {
            $("#startDateDiv").show();
            $("#endDateDiv").show();
            $("#searchDiv").show();
        }
    }

    // Date Range filter

    $('body').on('click', '.js_on_submit_filter', function (e) {
        // e.preventDefault();
        $('#start_date').removeAttr('name');
        // return false;
    });

    $(function() {
        var format = 'MM-DD-YYYY';
        var formatDB = 'YYYY-MM-DD';

        var start = moment().subtract(1, 'years');
        var end = moment();

        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        if (startDate && startDate.length === 10 && endDate && endDate.length === 10) {
            start = moment(startDate, 'YYYY-MM-DD');
            end = moment(endDate, 'YYYY-MM-DD');
        }
        var value = start.format(format) + ' - ' + end.format(format);
        
        $('#id-date-range').val(value);
        $('#start_date').val(start.format(formatDB));
        $('#end_date').val(end.format(formatDB));

        $('input[name="dateRange"]').daterangepicker({
            locale: {
                format: format
            },
            opens: 'left',
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {

            $('#start_date').val(start.format(formatDB));
            $('#end_date').val(end.format(formatDB));
        });
    });


    window.currentRecommendationData = @json($recommendationGraphArr ?? []);

    let recommendationChart = null;

    /*
    ** Recommendation Chart Generation
    */

    document.addEventListener("DOMContentLoaded", function() 
    {
        getFilter(); // To handle the display of date range filter on page load based on the selected value

        var preferredChartType = "{{ $preferredChartType }}";
        generateRecommendationChart(preferredChartType, window.currentRecommendationData);
    });

</script>
@endsection
