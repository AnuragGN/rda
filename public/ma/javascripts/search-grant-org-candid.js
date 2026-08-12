
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

    var guideStarEmpty = '<a onclick="onShowCandidSearchModal();" class="link_search_candid" href="javascript:void(0);">Search on  GuideStar (Now Candid)</a>';
    var guideStarFooter = '<a onclick="onShowCandidSearchModal();" class="link_search_candid footer" href="javascript:void(0);">Search on  GuideStar (Now Candid)</a>';

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
                    guideStarEmpty,
                    '</div>'
                ].join('\n'),
                footer: guideStarFooter,
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

        // only for CCT - may 31, 2023
        const contactNameOut = $('#id_contact_name_out');
        if ( contactNameOut.length ) {
            contactNameOut.val(data.contact_name);
        }

        const contactTitleOut = $('#id_contact_title_out');
        if ( contactTitleOut.length ) {
            contactTitleOut.val(data.contact_title);
        }

        const contactEmailOut = $('#id_contact_email_out');
        if ( contactEmailOut.length ) {
            contactEmailOut.val(data.contact_email);
        }

        const contactPhoneOut = $('#id_contact_phone_out');
        if ( contactPhoneOut.length && data.contact_phone.length > 9) {
            contactPhoneOut.val(data.contact_phone);
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

