
/**********************************************************************************************************************/
// search org for recommendation & donations
/**********************************************************************************************************************/

// moved in search-grant-org.gs and search-grant-org-candid.gs

/**********************************************************************************************************************/
// search org for catalog
/**********************************************************************************************************************/
$(function(){
    // TAG: OrganizationTypeAhead
    var orgCatalogDS = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/films/post_1960.json',
        remote: {
            url: '/m/search/catalog/organizations?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    $('#id_org_catalog_typeahead .typeahead').typeahead(
        {
            minLength: 1,
            highlight: true
        },
        {
            name: 'id',
            display: 'label',
            source: orgCatalogDS,
            limit: Infinity,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'No matches',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<div>{{label}}, <span class="typeahead-org-address">{{address}}</span></div>')
            }
        }
    ).on('typeahead:selected', function(e, data) {
        console.log(data);
        console.log(data.id);

        $("#id_organization").val(data.id);
        // $("#id-searched-org-address").html(data.address);

        if (typeof onCatalogTypeAheadOrgSelected !== 'undefined' && $.isFunction(onCatalogTypeAheadOrgSelected)) {
            onCatalogTypeAheadOrgSelected(data.id);
        }

        // if ($.isFunction(window.onTypeAheadOrgSelected)) {
        //    onTypeAheadOrgSelected();
        // }
        // alert('On Selected, id= ' + data.id);
    }).on('change', function () {
        // $("#id_organization").val(0);
        // alert('On Changed');
    });

});


/**********************************************************************************************************************/
// search funds
/**********************************************************************************************************************/
$(function(){
    // TAG: FundTypeAhead
    var fundsDS = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/films/post_1960.json',
        remote: {
            url: '/m/search/funds?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    $('#id_fund_typeahead .typeahead').typeahead(
        {
            minLength: 1,
            highlight: true
        },
        {
            name: 'id',
            display: 'label',
            source: fundsDS,
            limit: Infinity,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'No matches',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<div>{{label}}, <span class="typeahead-fund-info">{{address}}</span></div>')
            }
        }
    ).on('typeahead:selected', function(e, data) {
        console.log(data);
        console.log(data.id);

        //<span class="typeahead-org-address">{{address}}</span>
        $("#id_fund").val(data.id);
        $("#id-searched-fund-info").html(data.address);

        // alert('On Selected, id= ' + data.id);
    }).on('change', function () {
        // $("#id_fund").val(0);
        // alert('On Changed');
    });

});
