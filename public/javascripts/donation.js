class JsDonation {
    constructor() {
    }

    init() {
        this.error = false;
        this.errors = '';

        this.id_form_container = $('#id_form_container');

        this.id_response_error = $('#id_response_error');
        this.id_error_list = $('#id_error_list');
        this.id_floating_amount = $('#id_floating_amount');
        this.id_btn_donate = $('#id_btn_donate');
        this.id_donation_form = $('#id_donation_form');

        this.id_dedicated_to = $('#id_dedicated_to');
        this.id_inform_to = $('#id_inform_to');
        this.id_on_behalf_of = $('#id_on_behalf_of');
        this.id_intervals = $('#id_intervals');
        this.id_end_date_view = $('#id_end_date_view');
        this.id_occurrences = $('#id_occurrences');
        this.id_fund_org_typeahead = $('#id_fund_org_typeahead');
        this.id_org_typeahead = $('#id_org_typeahead');
        this.id_fund_typeahead = $('#id_fund_typeahead');
    }

    onDedicate(item) {
        if (item.checked) {
            this.id_dedicated_to.show(400);
            console.log('checked');
        } else {
            console.log('un-checked');
            this.id_dedicated_to.hide(400);
        }
    }

    onInformTo(item) {
        if (item.checked) {
            this.id_inform_to.show(400);
            console.log('checked');
        } else {
            console.log('un-checked');
            this.id_inform_to.hide(400);
        }
    }

    onBehalfOf(item) {
        if (item.checked) {
            this.id_on_behalf_of.show(400);
            console.log('checked');
        } else {
            console.log('un-checked');
            this.id_on_behalf_of.hide(400);
        }
    }

    onChangeInterval(item) {
        var value = item.value;
        if (value == 'one') {
            this.id_intervals.hide(400);
        } else {
            this.id_intervals.show(400);
        }
    }

    onEndDate(item) {
        if (item.checked) {
            this.id_end_date_view.hide(400);
        } else {
            this.id_end_date_view.show(400);
        }
    }

    onChangeSearch(item) {
        console.log('Search: ' + item.value);
        if (item.value == 'funds') {
            this.id_fund_org_typeahead.hide();
            this.id_org_typeahead.hide();
            this.id_fund_typeahead.show();
        } else if (item.value == 'orgs')  {
            this.id_fund_org_typeahead.hide();
            this.id_fund_typeahead.hide();
            this.id_org_typeahead.show();
        } else {
            this.id_fund_org_typeahead.show();
            this.id_fund_typeahead.hide();
            this.id_org_typeahead.hide();
        }
    }
    onShowDonationForm() {
        this.id_btn_donate.hide();
        this.id_donation_form.show();
        return false;
    }


    onContinue() {

        var result = this.validateDonationForm();
        if (!result) {
            console.log("Input validation failed");
            return;
        }
        // show payment method
        // jsDonation.save();
        $(".AcceptUI").click();
    }

    validateDonationForm() {

        var error = false;
        var errors = '';

        // clear all errors
        this.clearFieldErrors();

        var values = {};
        $.each($('#id_donation_form').serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });
        console.log('values: ' + JSON.stringify(values));

        // search option
        if (values.search_option == 'all') {

        } else if (values.search_option == 'orgs') {
            if (!values.organization_id) {
                error = true;
                this.setFieldError('#id_organization_name');
                console.log('Organization Id is not set!');
            }
        } else if (values.search_option == 'funds') {
            if (!values.fund_id) {
                this.setFieldError('#id_fund_name');
                console.log('Fund Id is not set!');
            }
        } else {
            console.log('Unknown search option');
        }

        // amount
        var minAmount = 100;
        if (values.amount == undefined || values.amount < minAmount) {
            console.log('Amount must not be less than $100');
            this.setFieldError('#id_floating_amount');
        }

        // interval
        if (values.interval != 'one') {
            if (!values.start_date) {
                console.log('Start date is not set');
                this.setFieldError('#id_start_date');
            }
            if (!values.no_end) {
                if (!values.occurrences) {
                    console.log('Occurrences is not set');
                    this.setFieldError('#id_occurrences');
                }
            }
        }

        // dedicated
        if (values.dedicated) {
            if (!values.dedicated_to_name) {
                this.setFieldError('#id_dedicated_to_name');
                console.log('Name of person is not set');
            }
        }

        // notify
        if (values.notify) {
            if (!values.notify_fname) {
                this.setFieldError('#id_notify_fname');
                console.log('Notify - first name is not set');
            }
            if (!values.notify_lname) {
                this.setFieldError('#id_notify_lname');
                console.log('Notify - last name is not set');
            }
            if (!values.notify_address_one) {
                this.setFieldError('#id_notify_address_one');
                console.log('Notify - address line one is not set');
            }
            if (!values.notify_city) {
                this.setFieldError('#id_notify_city');
                console.log('Notify - address city is not set');
            }
            if (!values.notify_state) {
                this.setFieldError('#id_notify_state');
                console.log('Notify - address state is not set');
            }
            if (!values.notify_country) {
                this.setFieldError('#id_notify_country');
                console.log('Notify - address country is not set');
            }
            if (!values.notify_zip) {
                this.setFieldError('#id_notify_zip');
                console.log('Notify to ZIP is not set');
            }
        }

        // personal info
        if (!values.guest_fname) {
            this.setFieldError('#id_fname');
            console.log('First name is not set: ' + values.fname);
        }
        if (!values.guest_lname) {
            this.setFieldError('#id_lname');
            console.log('Last name is not set');
        }
        if (!values.guest_email) {
            this.setFieldError('#id_email');
            console.log('Email is not set');
        } else {
            if (!isValidEmail(values.guest_email)) {
                this.setFieldError('#id_email');
                console.log('Email is invalid');
            }
        }

        // address
        if (!values.guest_address_one) {
            this.setFieldError('#id_address_one');
            console.log('Address line one is not set');
        }
        if (!values.guest_city) {
            this.setFieldError('#id_city');
            console.log('Address city is not set');
        }
        if (!values.guest_state) {
            this.setFieldError('#id_state');
            console.log('State is not set');
        }
        if (!values.guest_country) {
            this.setFieldError('#id_country');
            console.log('Address country is not set');
        }
        if (!values.guest_zip) {
            this.setFieldError('#id_zip');
            console.log('Guest ZIP is not set');
        }

        if (this.error) {
            this.id_error_list.show();
            $('html, body').animate({
                scrollTop: this.id_error_list.offset().top
            }, 1200);
        }
        return !this.error;
    }

    setFieldError(item) {
        this.error = true;
        $(item).addClass('field-error');
    }

    clearFieldErrors() {
        this.error = false;
        this.errors = '';
        this.id_error_list.hide();
        this.id_floating_amount.removeClass('field-error');

        $('#id_organization_name').removeClass('field-error');
        $('#id_fund_name').removeClass('field-error');

        $('#id_start_date').removeClass('field-error');
        $('#id_occurrences').removeClass('field-error');

        $('#id_dedicated_to_name').removeClass('field-error');
        $('#id_notify_fname').removeClass('field-error');
        $('#id_notify_lname').removeClass('field-error');
        $('#id_notify_address_one').removeClass('field-error');
        $('#id_notify_city').removeClass('field-error');
        $('#id_notify_state').removeClass('field-error');
        $('#id_notify_country').removeClass('field-error');
        $('#id_notify_zip').removeClass('field-error');

        $('#id_fname').removeClass('field-error');
        $('#id_lname').removeClass('field-error');
        $('#id_email').removeClass('field-error');
        $('#id_address_one').removeClass('field-error');
        $('#id_city').removeClass('field-error');
        $('#id_state').removeClass('field-error');
        $('#id_country').removeClass('field-error');
        $('#id_zip').removeClass('field-error');
    }

    hideModal() {
        setTimeout(function (){
            console.log('hiding modal');
            $('#id_transaction_in_progress').modal('hide');
        }, 600);
    }

    save() {

        console.log('on save');

        var _this = this;

        var modalItem = $('#id_transaction_in_progress');
        modalItem.modal('show');

        var formData = this.id_donation_form.serialize();
        var eMessage  = "Some error occurred while processing your request!";

        $.ajax({
            url: '/donation',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function (data) {

                _this.hideModal();
                console.log('Response success. Data: ', data);

                if (data.status == 200) {
                    console.log('SUCCESS!');
                    _this.onSuccessResponse(data);
                } else if (data.status == 422) {
                    console.log('Error 422!');
                    _this.onErrorResponse(data.message ? data.message : eMessage);
                } else {
                    console.log('Error other!');
                    _this.onErrorResponse(data.message ? data.message : eMessage);
                }
            },
            error: function (e) {

                _this.hideModal();
                console.log('Response error');
                _this.onErrorResponse(eMessage);

                if (e.status === 422) {
                    var errors = $.parseJSON(e.responseText);
                    console.log("errors: ", errors);
                    $.each(errors, function (key, val) {
                        console.log("K: ", key, " V: ", val);
                        // $("#error_mesg_login").text(val);
                    });
                }
            }
        });
        return false;
    }

    onErrorResponse(mesg) {
        this.id_response_error.html(mesg);
        this.id_response_error.show();
        $('html, body').animate({
            scrollTop: this.id_response_error.offset().top
        }, 1200);
    }
    onSuccessResponse(response) {
        $('#id_btn_home').show();
        this.id_form_container.html(response.html);
    }

}

var jsDonation = new JsDonation();

// auth-net handling

function donationResponseHandler(response) {
    if (response.messages.resultCode === "Error") {
        var i = 0;
        while (i < response.messages.message.length) {
            alert(response.messages.message[i].text + "(" + response.messages.message[i].code + ")");
            console.log(
                response.messages.message[i].code + ": " +
                response.messages.message[i].text
            );
            i = i + 1;
        }
    } else {
        // console.log(response);
        paymentFormUpdate(response.opaqueData);
    }
}

function paymentFormUpdate(opaqueData) {
    document.getElementById("id_data_descriptor").value = opaqueData.dataDescriptor;
    document.getElementById("id_data_value").value = opaqueData.dataValue;
    clearForm();
    jsDonation.save();
    // document.getElementById("id_donation_form").submit();
}

function clearForm() {
    // var doc = document.getElementById('myframe1').contentWindow.document.getElementById('x');
}

