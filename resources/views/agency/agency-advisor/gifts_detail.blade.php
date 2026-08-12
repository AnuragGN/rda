<?php
$pageTitle = 'Gifts Dashboard';
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
                    <div class="chart-box mt-2">
                        <div class="title" id="headignLabel">Fund-Wise Gifts <small>[Last 30 Days]</small></div>
                        <div class="row" id="fundDiv">

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


function get_organization_fund_wise(fund_id) {
    
    var isClosed = false;
    $(".fund_"+fund_id).filter(function() 
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
                url: "{{ route('agency-gift-ajax') }}",
                data: { "fund_id":fund_id,'grant_type': grant_type, 'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
                dataType: 'json',
                success: function (data) {

                    var dynamicDataPoints = [];
                    for (var j in data['0'].donor_data) {

                        var donor_data = data['0'].donor_data[j];
                        dynamicDataPoints.push({
                            label: donor_data.contact_name,
                            y: donor_data.total_donor_grant
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

function getFundGrantsDetails() {

    var grant_type = $("#grant_type").val();
    var grant_date_range = $("#grant_date_range").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    $.ajax({

        type: 'GET',
        url: "{{ route('agency-gift-ajax') }}",
        data: { "fund_id":'','grant_type': grant_type, 'grant_date_range': grant_date_range, 'start_date': start_date, 'end_date': end_date },
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
                                    <a style="cursor:pointer;" onclick="get_organization_fund_wise('${ data[i].fund_id }')" class="pool-kv js_toggle_pool_values_fa_fund" 
                                    title="Click to Expand / Collapse" data-target-id="fundGiftbutionDiv-${ data[i].fund_id }">
                                        <span class="name">
                                        <small id="id_pool_open" class="id_pool_open_fa fund_${ data[i].fund_id }" style="display: none;"><i class="fas fa-minus-circle toggle-icon"></i></small>
                                        <small id="id_pool_closed" class="id_pool_closed_fa" style="display: inline;"><i class="fas fa-plus-circle toggle-icon"></i></small>
                                        ${ data[i].fund_name }</span>
                                        <span class="amount"> ${ total_fund_grant_format }</span>
                                    </a>
                                    
                                    <div class="pool-values fund_fa" id="fundGiftbutionDiv-${ data[i].fund_id }" style="display: none;">`;
                                        for (var j in data[i].donor_data) {

                                            var donor_data = data[i].donor_data[j];

                                            fund_html += 
                                            `<div class="fund-kv">
                                                <span>${donor_data.contact_name}</span>
                                                <span>${donor_data.total_donor_grant_format}</span>
                                             </div>`;
                                        }
                                        fund_html += 
                                    `</div>
                                    </div>
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

function getTextlable(type) {

    var grant_type = $("#grant_type").val();
    var grant_date_range = $("#grant_date_range").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var id_date_range = $("#id-date-range").val();

    var label = '';
    if(grant_type == 'Fund Wise'){

        label = 'Fund-Wise Gifts';
    }

    if(grant_type == 'Donor Wise'){

        label = 'Donor-Wise Gifts';
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

