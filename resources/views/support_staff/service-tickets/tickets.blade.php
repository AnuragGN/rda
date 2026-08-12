@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Tickets', 'hcXlWidth' => 12])
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
                                <div class="col-xl-7 col-sm-12"><h3>Ticket List</h3></div>
                                <div class="col-xl-5 col-sm-12 text-right">
                                    <a href="javascript:void(0);" style="background-color: #e6f4f7;"
                                       onclick="sageCollapsible(this)" data-child-id="statement-filter"
                                       class="btn btn-light btn-sm shadowed mr-2 ml-2">
                                        Filter <i class="fas fa-filter"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="GET" accept-charset="UTF-8" id="form-history-filter" class="">
                        <div class="row" id="statement-filter" style="display: none">
                            <div class="col-12">
                                <div class="filter-row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Search</b></small></label>
                                            </div>
                                            <div style="display: flex">
                                                <div class="mb-2 mr-sm-2" style="width: 100%">
                                                    <div id="id_org_catalog_typeahead" class="catalog-ta-search mt-32">
                                                        <input id="search_ticket" name="search_ticket"
                                                        type="text" class="form-control" placeholder="Search Ticket by Id, Text..">
                                                        <button class="btn btn-light org-search" type="button" 
                                                        onclick="getTicketList()"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Ticket Status</b></small></label>
                                            </div>

                                            <div style="display: flex">
                                                <div class="mb-2 mr-sm-2" style="width: 100%">
                                                    <select class="form-control" id="status" 
                                                    onchange="getTicketList();">
                                                        <option value="">All Status</option>
                                                        @foreach($statusDropdown as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Ticket Priority</b></small></label>
                                            </div>

                                            <div style="display: flex">
                                                <div class="mb-2 mr-sm-2" style="width: 100%">
                                                    <select class="form-control" id="priority" onchange="getTicketList();">
                                                        <option value="">All Priority</option>
                                    
                                                        @foreach($priorityDropdown as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label class="col-sm-6 col-form-label"><small><b>Ticket Type</b></small></label>
                                            </div>
                                            <div style="display: flex">
                                                <div class="mb-2 mr-sm-2" style="width: 100%">
                                                     <select class="form-control" id="category" onchange="getTicketList();">
                                                        <option value="">All Ticket Type</option>
                                                        @foreach($categoryDropdown  as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @include('support_staff.service-tickets.list-loader')
                    <br>
                </div>
                <div class="col-xl-5 col-r-15">
                    <h3 class="page-subtitle mt-3">
                        Ticket Graph
                    </h3>
                    <br>
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                </div>  
            </div>
        </div>
    </div>
@include('agency.agency-advisor.common-script')    
<script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
<script>
getTicketList();
function getTicketList() {

    var search_ticket = $.trim($("#search_ticket").val());
    var status = $.trim($("#status").val());
    var priority = $.trim($("#priority").val());
    var category = $.trim($("#category").val());
   
    jsTicketLoader.init('/m/support-staff/ticket-ajax');
    jsTicketLoader.runFilterData(search_ticket,status, priority, category);
    jsTicketLoader.runLoadData(); 
}
</script>
@endsection
