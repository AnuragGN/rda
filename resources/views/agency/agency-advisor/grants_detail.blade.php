<?php
$pageTitle = 'Grants Dashboard';
?>
@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => 12])

<style>
.canvasjs-chart-credit{
    display: none;
}
</style>
    <div class="container history-container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                    <div class="row">
                         <div class="col-md-6">
                            <select class="form-control" id="grant_type" name="grant_type" 
                            onchange="getGrantFilter();">
                                <option value="Fund Wise">Fund Wise</option>
                                <option value="Organization Wise">Organization Wise</option>
                                <option value="Donor Wise">Donor Wise</option>
                            </select>
                        </div>
                         
                        <div class="col-md-6">
                            <select class="form-control" id="grant_date_range" name="grant_date_range" 
                            onchange="getGrantFilter();">
                                <option value="Last 30 Days">Last 30 Days</option>
                                <option value="Last Month">Last Month</option>
                                <option value="Last 3 Months">Last 3 Months</option>
                                <option value="Last 6 Months">Last 6 Months</option>
                                <option value="Last 1 Year">Last 1 Year</option>
                                <option value="Custom Date">Custom Date</option>
                            </select>
                        </div>
                        <div class="col-md-12" id="startDateDiv" style="display:none;">
                            <div class="row">
                                <label class="col-sm-12 col-form-label" for="inlineFormInputName2">Select Period</label>
                            </div>
                            <div style="display: flex">
                                <input type="text" id="id-date-range" name="dateRange" class="form-control mb-2 mr-sm-2"  value="12/01/2023 - 01/31/2024" />
                                <input id="start_date" name="start_date" type="hidden" value="1">
                                <input id="end_date" name="end_date" type="hidden" value="3">
                                <button type="submit" class="btn btn-accent mb-2 js_on_submit_filter" 
                                onclick="searchCustomGrants()">Search</button>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top:7px;display: none;">
                        <div class="col-md-4" id="startDateDiv" style="display:none;">
                            <input type="date" class="form-control" id="start_date" placeholder="Start Date" 
                            name="start_date">
                        </div>
                        <div class="col-md-4" id="endDateDiv" style="display:none;">
                            <input type="date" class="form-control" id="end_date" placeholder="End Date" 
                            name="end_date">
                        </div>
                        <div class="col-md-2" id="searchDiv" style="display:none;">
                            <input name="search_grant" id="search_grant" class="btn btn-accent" type="button" 
                            value="Search" onclick="searchCustomGrants()">
                        </div>
                    </div>

                    <div class="chart-box mt-2">
                        <div class="title" id="headignLabel">Fund-Wise Grants <small>[Last 30 Days]</small></div>
                        <div class="row" id="fundDiv">

                        </div>
                        <div class="row" id="orgDiv" style="display:none;">
                            
                        </div>
                        <div class="row" id="donorDiv" style="display:none;">
                            
                        </div>
                    </div>
                    <a href="{{ route('agency-home') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-10 col-12">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
                <input type="hidden" id="hyd_label" value="">
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
setTimeout(function() {

    getGrantFilter();
}, 1000);

function getGrantFilter() {

    $("#fundDiv").hide();
    $("#orgDiv").hide();
    $("#donorDiv").hide();

    $("#startDateDiv").hide();
    $("#endDateDiv").hide();
    $("#searchDiv").hide();

    var grant_type = $("#grant_type").val();
    var grant_date_range = $("#grant_date_range").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    if(grant_type == 'Fund Wise'){

        $("#fundDiv").show();
    }

    if(grant_type == 'Organization Wise') {

        $("#orgDiv").show();
    }

    if(grant_type == 'Donor Wise'){

        $("#donorDiv").show();
    }

    if(grant_date_range == 'Custom Date') {

        $("#startDateDiv").show();
        $("#endDateDiv").show();
        $("#searchDiv").show();

       searchCustomGrants();

    } else {

        getFundGrantsDetails();
    }  

    var display_label = getTextlable('');
    $("#headignLabel").html(display_label);
}

function searchCustomGrants() {

    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    var display_label = getTextlable('');
    $("#headignLabel").html(display_label);
    getFundGrantsDetails();
}

function get_organization_fund_wise(fund_id,flag) {
    
    var isClosed = false;
    $(".fund_"+fund_id).filter(function() 
    {
        isClosed = $(this).css("display") == "none";
        
        if(isClosed == true || flag == 1)
        {
            var grant_type = $("#grant_type").val();
            var grant_date_range = $("#grant_date_range").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();

            $.ajax({

                type: 'GET',
                url: "{{ route('agency-grants-ajax') }}",
                data: { "fund_id":fund_id,"org_id":'','grant_type': grant_type, 'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
                dataType: 'json',
                success: function (data) {

                    var dynamicDataPoints = [];
                    for (var j in data['0'].organization_data) {

                        var org_data = data['0'].organization_data[j];
                        var total_org_grant     = org_data.total_org_grant;
                        var organization_name   = org_data.organization_name;

                         // Code for Graph
                        dynamicDataPoints.push({
                            label: organization_name,
                            y: total_org_grant
                        });
                    }
                    // Display Graph

                    var display_label = getTextlable('graph');

                    var options = {
                        animationEnabled: true,
                        title: {
                            text: display_label,
                            fontFamily: "Arial", 
                            fontSize: 18,
                        },
                        axisY: {
                            labelFormatter: function (e) {
                                return '$' + e.value.toLocaleString('en-US');
                            }
                        },
                        data: [{
                            type: "doughnut",
                            innerRadius: "30%",
                            showInLegend: true,
                            legendText: "{label}",
                            indexLabel: "{label}: ${y}",
                            dataPoints: dynamicDataPoints
                        }]
                    };
                    $("#chartContainer").CanvasJSChart(options);
                    // End 
                }
            });
        }
        else
        {
            getFundGrantsDetails();
        }
    });
}

function get_donor_organization_fund_wise(fund_id,org_id) {

    var isClosed = false;
    $(".fund_org_"+fund_id+"_"+org_id).filter(function() 
    {
        isClosed = $(this).css("display") == "none";
        
        if(isClosed == true)
        {
            var grant_type = $("#grant_type").val();
            var grant_date_range = $("#grant_date_range").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();

            $.ajax({

                type: 'GET',
                url: "{{ route('agency-grants-ajax') }}",
                data: { "fund_id":fund_id,"org_id":org_id,'grant_type':grant_type, 'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
                dataType: 'json',
                success: function (data) {

                    var dynamicDataPoints = [];
                    var response = data[0].organization_data[0];
                    for (var k in response.donor_data) {

                        var donor_data1 = response.donor_data[k];
                        var total_donor_grant     = donor_data1.total_donor_grant;
                        var contact_name   = donor_data1.contact_name;
                        
                         // Code for Graph
                        dynamicDataPoints.push({
                            label: contact_name,
                            y: total_donor_grant
                        });
                    }
                    // Display Graph

                    var display_label = getTextlable('graph');

                    var options = {
                        animationEnabled: true,
                        title: {
                            text: display_label,
                            fontFamily: "Arial", 
                            fontSize: 18,
                        },
                        axisY: {
                            labelFormatter: function (e) {
                                return '$' + e.value.toLocaleString('en-US');
                            }
                        },
                        data: [{
                            type: "doughnut",
                            innerRadius: "30%",
                            showInLegend: true,
                            legendText: "{label}",
                            indexLabel: "{label}: ${y}",
                            dataPoints: dynamicDataPoints
                        }]
                    };
                    $("#chartContainer").CanvasJSChart(options);
                    // End 
                }
            });
        }
        else
        {
            get_organization_fund_wise(fund_id,1);
        }
    });
}

function getFundGrantsDetails() {

    var grant_type = $("#grant_type").val();
    var grant_date_range = $("#grant_date_range").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    $.ajax({

        type: 'GET',
        url: "{{ route('agency-grants-ajax') }}",
        data: { "fund_id":'',"org_id":'','grant_type': grant_type, 'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
        dataType: 'json',
        success: function (data) {

            var dynamicDataPoints = [];  // Creating Array for Graph

            if(grant_type == 'Fund Wise') {

                var fund_html = ``;
                if (data.length > 0) {

                    for (var i in data) {

                        var total_fund_grant = data[i].total_fund_grant;
                        var total_fund_grant_format  = data[i].total_fund_grant_format ;
                       

                       // Code for Graph
                        dynamicDataPoints.push({
                            label: data[i].fund_name,
                            y: total_fund_grant
                        });
                        // End

                        fund_html += 
                        `<div class="col-12">
                            <div class="fund-pool pool-default">
                                <a style="cursor:pointer;" onclick="get_organization_fund_wise('${ data[i].fund_id }',0)" class="pool-kv js_toggle_pool_values_fa_fund" 
                                title="Click to Expand / Collapse" data-target-id="fundGrantDiv-${ data[i].fund_id }">
                                    <span class="name">
                                        <small id="id_pool_open" class="id_pool_open_fa fund_${ data[i].fund_id }" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                        <small id="id_pool_closed" class="id_pool_closed_fa" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                            ${ data[i].fund_name }
                                    </span>
                                    <span class="amount"> ${ total_fund_grant_format }</span>
                                </a>
                                <div class="pool-values fund_fa" id="fundGrantDiv-${ data[i].fund_id }" style="display: none;">`;

                                    for (var j in data[i].organization_data) {

                                        var org_data = data[i].organization_data[j];

                                        fund_html += 
                                        `<div class="">
                                            <a style="cursor:pointer;" onclick="get_donor_organization_fund_wise('${ data[i].fund_id }','${ org_data.organization_id }')" class="pool-kv js_toggle_pool_values_fa_fund_org" title="Click to Expand / Collapse" data-target-id="orgGrantDiv-${ data[i].fund_id }-${ org_data.organization_id }">
                                                <span class="name" style="font-weight: 450;">
                                                    <small id="id_pool_open" class="id_pool_open_fa_org fund_org_${ data[i].fund_id }_${ org_data.organization_id }" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                                    <small id="id_pool_closed" class="id_pool_closed_fa_org" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                                    ${org_data.organization_name}
                                                </span>
                                                <span class="amount" style="font-weight: 400;"> 
                                                ${ org_data.total_org_grant_format }</span>
                                            </a>
                                            <div class="pool-values fund_org_fa" id="orgGrantDiv-${ data[i].fund_id }-${ org_data.organization_id }" style="display: none;">`;

                                                for (var k in org_data.donor_data) {

                                                    var donor_data = org_data.donor_data[k];

                                                    fund_html += 
                                                    `<div class="fund-kv">
                                                        <span><small><a target="_blnak" href="/m/agency/client/${donor_data.contact_id}">${donor_data.contact_name}</a></small></span>
                                                        <span><small>${donor_data.total_donor_grant_format}</small></span>
                                                    </div>`;
                                                }
                                        fund_html += `</div>
                                        </div>`
                                    }
                            fund_html += `</div>
                            </div>
                        </div>`;
                    }

                } else {

                    fund_html += `
                        <div class="col-12">
                            <span><i class="fas fa-exclamation-triangle"></i> No data available.</span>
                        </div>`;
                }
                $("#fundDiv").html(fund_html);
            }

            if(grant_type == 'Organization Wise') {

                var org_html = `
                    <div class="col-12">
                        <div class="fund-pool pool-default">`;
                            if (data.length > 0) {

                                for (var i in data) {

                                    var total_org_grant = data[i].total_org_grant;
                                    var total_org_grant_format  = data[i].total_org_grant_format;
                                    
                                    // Code for Graph
                                    dynamicDataPoints.push({
                                        label: data[i].organization_name,
                                        y: total_org_grant
                                    });
                                    // End

                                    org_html += `
                                    <div class="fund-kv">
                                        <span>${ data[i].organization_name }</span>
                                        <span>${ data[i].total_org_grant_format }</span>
                                    </div>`;
                                }

                            } else {

                                org_html += `
                                <div class="col-12">
                                    <span><i class="fas fa-exclamation-triangle"></i> No data available.</span>
                                </div>`;
                            }
                        org_html += 
                        `</div>
                    </div>`;
                $("#orgDiv").html(org_html);
            }

            if(grant_type == 'Donor Wise') {

                var donor_html = `
                <div class="col-12">
                    <div class="fund-pool pool-default">`;
                        if (data.length > 0) {

                            for (var i in data) {

                                var total_donor_grant = data[i].total_donor_grant;
                                var total_donor_grant_format  = data[i].total_donor_grant_format;
                                
                                // Code for Graph
                                dynamicDataPoints.push({
                                    label: data[i].contact_name,
                                    y: total_donor_grant
                                });
                                // End

                                donor_html += `
                                <div class="fund-kv">
                                    <span>${ data[i].contact_name }</span>
                                    <span>${ data[i].total_donor_grant_format }</span>
                                </div>`;
                            }
                            
                        } else {

                            donor_html += `
                            <div class="col-12">
                                <span><i class="fas fa-exclamation-triangle"></i> No data available.</span>
                            </div>`;
                        }
                    donor_html += 
                    `</div>
                </div>`;
                $("#donorDiv").html(donor_html);
            }

            // Display Graph

            var display_label = getTextlable('graph');

            var options = {
                animationEnabled: true,
                title: {
                    text: display_label,
                    fontFamily: "Arial", 
                    fontSize: 18,
                },
                axisY: {
                    labelFormatter: function (e) {
                        return '$' + e.value.toLocaleString('en-US');
                    }
                },
                data: [{
                    type: "doughnut",
                    innerRadius: "30%",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabel: "{label}: ${y}",
                    dataPoints: dynamicDataPoints
                }]
            };
            $("#chartContainer").CanvasJSChart(options);
            // End 
        }
    });
}

function getTextlable(type){

    var grant_type = $("#grant_type").val();
    var grant_date_range = $("#grant_date_range").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var id_date_range = $("#id-date-range").val();

    var label = '';
    if(grant_type == 'Fund Wise'){

        label = 'Fund-Wise Grants';
    }

    if(grant_type == 'Organization Wise') {

        label = 'Organization-Wise Grants';
    }

    if(grant_type == 'Donor Wise'){

        label = 'Donor-Wise Grants';
    }

    if(grant_date_range == 'Custom Date') {

        var display_label = label+' ['+grant_date_range+']';

        if(start_date != '' && end_date != '') {

            var display_label = label+' <small>[Period: '+id_date_range+']</small>';
            var display_label_graph = label+' [Period: '+id_date_range+']';
        }

    } else {
        var display_label = label+' <small>['+grant_date_range+']</small>';
        var display_label_graph = label+' ['+grant_date_range+']';
    }
    if(type == 'graph'){
        return display_label_graph;
    }
    else{
        return display_label;
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
</script>