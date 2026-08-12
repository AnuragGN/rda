
class JsRequestInfoFrom {
    constructor() {}

    init() {
        this.id_form_req_info = $('#id_form_req_info');
        this.id_req_info_modal = $('#id_req_info_modal');
        this.id_req_info_response_modal = $("#id_req_info_response_modal");
        this.id_info_error = $('#id_info_error');

        this.id_name = $('#id_name');
        this.id_phone = $('#id_phone');
        this.id_email = $('#id_email');
        this.id_comment = $('#id_comment');

        this.id_name_err = $('#id_name_err');
        this.id_phone_err = $('#id_phone_err');
        this.id_email_err = $('#id_email_err');
        this.id_comment_err = $('#id_comment_err');
    }

    onShowMoreInfo() {
        console.log('show');
        this.id_info_error.hide();
        this.id_name_err.hide();
        this.id_phone_err.hide();
        this.id_email_err.hide();
        this.id_comment_err.hide();
        this.id_req_info_modal.modal('show');
    }

    onHideReqMoreInfo() {
        console.log('show');
        this.id_req_info_modal.modal('hide');
    }

    hideErrorResponse() {
        this.id_info_error.hide();
    }
    showErrorResponse() {
        this.id_req_info_modal.scrollTop(0);
        this.id_info_error.show();
    }

    onShowResponse() {
        this.id_req_info_response_modal.modal('show');
    }

    onHideResponse() {
        this.id_req_info_response_modal.modal('hide');
    }

    validateInput() {
        var name = this.id_name.val();
        var phone = this.id_phone.val();
        var email = this.id_email.val();
        var comment = this.id_comment.val();

        this.id_name_err.hide();
        this.id_phone_err.hide();
        this.id_email_err.hide();
        this.id_comment_err.hide();

        if (!name || name.length < 1) {
            this.id_name_err.show();
            return false;
        }
        if (!phone || phone.length < 12) {
            this.id_phone_err.show();
            return false;
        }
        if (!email || email.length < 9) {
            this.id_email_err.show();
            return false;
        }

        var isChecked = false;
        $("input[name='actions[]']:checked").each(function () {
            isChecked = true;
        });

        if (!isChecked && (!comment || comment.length < 1)) {
            this.id_comment_err.show();
            return false;
        }
        return true;
    }

    onSubmit(item) {

        this.hideErrorResponse();

        if (!this.validateInput()) {
            console.log("validation error!");
            return;
        }

        console.log('on submit');
        var formData = this.id_form_req_info.serialize();

        $(item).find('#id_rmi_submit').hide();
        $(item).find('#id_rmi_in_porgress').show();
        $(item).addClass('disabled');

        var _this = this;
        $.ajax({
            url: '/request-info',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function (data) {

                console.log('Response success. Data: ', data);
                if (data.status == 200) {
                    console.log('SUCCESS 200');
                    _this.id_comment.val('');
                    _this.id_comment_err.html();
                    _this.onHideReqMoreInfo();
                    _this.onShowResponse();
                } else {
                    console.log('Error !200');
                    _this.showErrorResponse();
                }
            },
            error: function (e) {
                console.log('Error');
                _this.showErrorResponse();
            },
            complete(jqXHR, status) {
                $(item).find('#id_rmi_submit').show();
                $(item).find('#id_rmi_in_porgress').hide();
                $(item).removeClass('disabled');
            }
        });
    }
}

var jsReqInfoForm = new JsRequestInfoFrom();
