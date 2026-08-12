/**********************************************************************************************************************/
// search org for recommendation & donations
/**********************************************************************************************************************/
$(function(){
    // TAG: OrganizationTypeAhead
    var organizationsDS = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        // prefetch: '../data/films/post_1960.json',
        remote: {
            url: '/m/search/organizations?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    $('#id_org_typeahead .typeahead').typeahead(
        {
            minLength: 1,
            highlight: true
        },
        {
            name: 'id',
            display: 'label',
            source: organizationsDS,
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

        //<span class="typeahead-org-address">{{address}}</span>
        $("#id_organization").val(data.id);
        $("#id-searched-org-address").html(data.address);

        if (typeof grantForm !== 'undefined' && grantForm != null) {
            grantForm.onTypeAheadOrgSelected();
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

