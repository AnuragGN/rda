
class GrantFrom {
    constructor() {
        this.is_candid_org = false;
        this.client_id = null;
    }

    init() {
        this.ide_candid_search =$('#ide_candid_search');
        this.id_candid_find_now =$('#id_candid_find_now');
        this.id_candid_find_in_progress =$('#id_candid_find_in_progress');
        this.id_candid_search_text =$('#id_candid_search_text');

        this.id_org_info_container = $('#id_org_info_container');
        this.id_organization = $('#id_organization');
        this.id_org_name_typeahead = $('#id_org_name_typeahead');
        this.id_add_org_btn = $('#id_add_org_btn');
        this.id_searched_org_address = $('#id-searched-org-address');

        this.icon_plus = $('.org-group i.fa-plus-circle');
        this.icon_minus = $('.org-group i.fa-minus-circle');

        var orgInfoView = $('#id_org_info');
        this.orgInfoView = orgInfoView.clone(true);
        orgInfoView.remove();
    }

    toggleOrgAddress() {
        if(this.id_org_info_container.css('display') == 'none'){
            this.showOrgAddress();
        } else {
            this.hideOrgAddress();
        }
    }

    hideOrgAddress() {
        this.icon_plus.removeClass('hide');
        this.icon_minus.removeClass('hide');
        this.icon_minus.addClass('hide');
        this.id_org_info_container.hide('slow');
    }

    showOrgAddress() {
        this.icon_minus.removeClass('hide');
        this.icon_plus.removeClass('hide');
        this.icon_plus.addClass('hide');
        this.id_org_info_container.show('slow');
    }

    onOrgBlur(item) {
        var id = $('#id_organization').val();
        var name = this.id_org_name_typeahead.val();
        console.log("Organization Id: " + id + ", Organization name: ", name);

        if ( (!id || id == 0 || id == null) && name.length > 0) {
            console.log("Append View to Container!");
            this.orgInfoView.appendTo('#id_org_info_container');

            this.id_add_org_btn.show();
            this.icon_plus.removeClass('hide');
            this.icon_minus.removeClass('hide');
            this.icon_minus.addClass('hide');
        } else {
            this.id_add_org_btn.hide();
            this.hideOrgAddress();
            this.id_org_info_container.html('');
        }
    }

    onTypeAheadOrgSelected() {
        console.log("onTypeAheadOrgSelected:");
        this.id_add_org_btn.hide();
        this.clearOrgAddressInfo();
        this.hideOrgAddress();
        this.id_org_info_container.html('');
    }

    onOrgChanged(item) {
        // added for Candid
        if (this.is_candid_org == true) {
            this.is_candid_org = false;
            this.clearOrgAddressInfo();
        }

        // clear selected organization, if any
        this.id_searched_org_address.html('');
        this.id_organization.val(0);
        console.log("onOrgChanged: Organization Id = ", this.id_organization.val());
    }

    onSave(item, event) {
        // validate organization id / organization info
        // e.preventDefault();

        var orgId = $('#id_organization').val();
        console.log("ON SAVE Id: ", orgId);

        // check if an org is selected
        if (orgId && orgId != 0 && orgId != null) {
            // continue with default input handling
            return true;
        }

        var name = this.id_org_name_typeahead.val();
        console.log("Organization name ", name);

        // check if org name is not set
        if (name.length == 0) {
            // continue with default input handling
            return true;
        }

        if (this.client_id == "cct") {
            var addLineOneItem = $('#id_address_one');
            var focusAddLineOne = addLineOneItem.val().length < 1;

            var cityItem = $('#id_city');
            var focusCity = cityItem.val().length < 1;
            console.log("focusAddLineOne:" + focusAddLineOne + ", focusCity: " + focusCity);

            if (!focusAddLineOne && !focusCity) return true;
            if (this.id_org_info_container.css('display') == 'none') this.showOrgAddress();

            if (focusAddLineOne) {
                addLineOneItem.focus();
                return false;
            }
            cityItem.focus();
        } else {
            // check required fields
            var phoneItem = $('#id_contact_phone');
            var phone = phoneItem.val();
            var phoneValid = (phone.length == 12);

            var email = $('#id_contact_email').val();
            var emailValid = isValidEmail(email);

            var ein = $('#id_org_ein').val();
            var einValid = (ein.length == 11);

            var addLineOne = $('#id_address_one').val();
            var city = $('#id_city').val();
            var addressValid = ((city.length > 1) && (addLineOne.length > 1));

            if (phoneValid || emailValid || einValid || addressValid) {
                // continue with default input handling
                return true;
            }
            if (this.id_org_info_container.css('display') == 'none') {
                this.showOrgAddress();
            }
            phoneItem.focus();
        }
        return false;
    }

    /***************************************************************************************************/
    // GuideStar / Candid Search
    /***************************************************************************************************/

    hideCandidSearchModal() {
        this.id_candid_find_now.show();
        this.ide_candid_search.hide();
        this.id_candid_find_in_progress.hide();
        $('#candidSearchModal').modal('hide');
    }

    onCandidSearch() {

        var _this = this;

        var query = this.id_candid_search_text.val();
        var eMessage  = "Your request could not be completed";

        if (!query || query.length < 3) {
            alert('Please enter 3 or more characters');
            return false;
        }

        // start search
        this.id_candid_find_now.hide();
        this.id_candid_find_in_progress.show();

        var formData = $("#candidSearchForm").serialize();

        console.log("formData: " + formData, formData);

        $.ajax({
            url: '/m/search-candid',
            type: 'POST',
            dataType: 'json',
            //data: {'q': query, },
            data: formData,
            success: function (data) {
                console.log('Response success. Data: ', data);
                if (data.status == 200) {
                    console.log('SUCCESS!');
                    _this.hideCandidSearchModal();
                    _this.onCandidSuccessResponse(data);
                } else if (data.status == 422) {
                    console.log('Error 422!');
                    _this.onCandidErrorResponse(data.message ? data.message : eMessage);
                } else {
                    console.log('Error other!');
                    _this.onCandidErrorResponse(data.message ? data.message : eMessage);
                }
            },
            error: function (e) {
                console.log('Response error');
                _this.onCandidErrorResponse(eMessage);
                if (e.status === 422) {
                    var errors = $.parseJSON(e.responseText);
                    console.log("errors: ", errors);
                    $.each(errors, function (key, val) {
                        console.log("K: ", key, " V: ", val);
                    });
                }
            }
        });
        return false;
    }

    onCandidErrorResponse(mesg) {
        this.ide_candid_search.show();
        this.id_candid_find_now.show();
        this.id_candid_find_in_progress.hide();
    }
    onCandidSuccessResponse(data) {

        $("#id_candid_search_results").html(data.html);
        setTimeout(function (){
            $('#candidResponseModal').modal('show');
        }, 300);

    }

    clearOrgAddressInfo(){
        $('#id_add_org_info_text').show();

        const contactName = $('#id_contact_name');
        const contactNameOut = $('#id_contact_name_out');
        if ( contactName.length ) {
            contactName.val('').prop("readonly", false);
        } else if ( contactNameOut.length ) {
            contactNameOut.val('').prop("readonly", false);
        }

        const contactTitle = $('#id_contact_title');
        const contactTitleOut = $('#id_contact_title_out');
        if (contactTitle.length) {
            contactTitle.val('').prop("readonly", false);
        } else if (contactTitleOut.length) {
            contactTitleOut.val('').prop("readonly", false);
        }

        const contactEmail = $('#id_contact_email');
        const contactEmailOut = $('#id_contact_email_out');
        if (contactEmail.length) {
            contactEmail.val('').prop("readonly", false);
        } else if (contactEmailOut.length) {
            contactEmailOut.val('').prop("readonly", false);
        }

        const contactPhone = $('#id_contact_phone');
        const contactPhoneOut = $('#id_contact_phone_out');
        if (contactPhone.length) {
            contactPhone.val('').prop("readonly", false);
        } else if (contactPhoneOut.length) {
            contactPhoneOut.val('').prop("readonly", false);
        }

        $('#id_org_ein').val('').prop("readonly", false);

        $('#id_address_one').val('').prop("readonly", false);
        $('#id_address_two').val('').prop("readonly", false);
        $('#id_city').val('').prop("readonly", false);
        $('#id_state').val('').prop("readonly", false);
        $('#id_org_zip').val('').prop("readonly", false);
    }

    onCandidOrgSelection(org) {
        //alert("onCandidOrgSelection.. 2");

        console.log("ORG: ", org);
        $('#id_add_org_info_text').hide();

        grantForm.showOrgAddress();

        // $('#id_org_name_typeahead').val(org.name);
        $('#id_org_typeahead .typeahead').typeahead('val', org.name);

        const contactName = $('#id_contact_name');
        const contactNameOut = $('#id_contact_name_out');
        if (org.contact_name && org.contact_name.length > 1) {
            if ( contactName.length ) {
                contactName.val(org.contact_name).prop("readonly", true);
            } else if ( contactNameOut.length ) {
                contactNameOut.val(org.contact_name).prop("readonly", true);
            }
        } else {
            if ( contactName.length ) {
                contactName.val('').prop("readonly", false);
            } else if ( contactNameOut.length ) {
                contactNameOut.val('').prop("readonly", false);
            }
        }

        const contactTitle = $('#id_contact_title');
        const contactTitleOut = $('#id_contact_title_out');
        if (org.contact_title && org.contact_title.length > 1) {
            if ( contactTitle.length ) {
                contactTitle.val(org.contact_title).prop("readonly", true);
            } else if ( contactTitleOut.length ) {
                contactTitleOut.val(org.contact_title).prop("readonly", true);
            }
        } else {
            if ( contactTitle.length ) {
                contactTitle.val('').prop("readonly", false);
            } else if ( contactTitleOut.length ) {
                contactTitleOut.val('').prop("readonly", false);
            }
        }

        const contactEmail = $('#id_contact_email');
        const contactEmailOut = $('#id_contact_email_out');
        if (org.contact_email && org.contact_email.length > 1) {
            if (contactEmail.length) {
                contactEmail.val(org.contact_email).prop("readonly", true);
            } else if (contactEmailOut.length) {
                contactEmailOut.val(org.contact_email).prop("readonly", true);
            }
        } else {
            if (contactEmail.length) {
                contactEmail.val('').prop("readonly", false);
            } else if (contactEmailOut.length) {
                contactEmailOut.val('').prop("readonly", false);
            }
        }

        const contactPhone = $('#id_contact_phone');
        const contactPhoneOut = $('#id_contact_phone_out');
        if (org.contact_phone && org.contact_phone.length > 1) {
            if (contactPhone.length) {
                contactPhone.val(org.contact_phone).prop("readonly", true);
            } else if (contactPhoneOut.length) {
                contactPhoneOut.val(org.contact_phone).prop("readonly", true);
            }
        } else {
            if (contactPhone.length) {
                contactPhone.val('').prop("readonly", false);
            } else if (contactPhoneOut.length) {
                contactPhoneOut.val('').prop("readonly", false);
            }
        }

        $('#id_org_ein').val(org.ein).prop("readonly", true);

        if (org.address_line_1 && org.address_line_1.length > 1) {
            $('#id_address_one').val(org.address_line_1).prop("readonly", true);
        } else {
            $('#id_address_one').val('').prop("readonly", false);
        }
        if (org.address_line_2 && org.address_line_2.length > 1) {
            $('#id_address_two').val(org.address_line_2).prop("readonly", true);
        } else {
            $('#id_address_two').val('').prop("readonly", false);
        }
        if (org.city && org.city.length > 1) {
            $('#id_city').val(org.city).prop("readonly", true);
        } else {
            $('#id_city').val('').prop("readonly", false);
        }
        if (org.state && org.state.length == 2) {
            $('#id_state').val(org.state).prop("readonly", true);
        } else {
            $('#id_state').val('').prop("readonly", false);
        }
        if (org.zip && org.zip.length > 1) {
            $('#id_org_zip').val(org.zip).prop("readonly", true);
        } else {
            $('#id_org_zip').val('').prop("readonly", false);
        }

        this.is_candid_org = true;
        $('#candidResponseModal').modal('hide');
    }

}

$(function(){
    $('#id_org_name_typeahead').on("input", function(){
        grantForm.onOrgChanged(this);
    }).blur(function() {
        grantForm.onOrgBlur();
    });
    //$("#id_org_info_container").on("input", "#id_contact_phone", function(event){
    //    console.log("xxxxxx");
    //    var val = this.value.replace(/\D/g, '');
    //    val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
    //    val = val.replace(/^(\d{3})-(\d{3})(.+)/, '$1-$2-$3');
    //    this.value = val.substring(0, 12);
    //    console.log("PHONE: ", $("#id_contact_phone").val());
    //});

    $("#id_org_info_container").on("input", "#id_org_zip", function(event){
        var val = this.value.replace(/\D/g, '');
        this.value = val.substring(0, 5);
    });

    $("#id_org_info_container").on("input", "#id_org_ein", function(event){
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{2})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{2})-(\d{7})(.+)/, '$1-$2');
        this.value = val.substring(0, 10);
    });


});
