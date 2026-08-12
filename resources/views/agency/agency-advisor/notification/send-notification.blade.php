<?php
use App\Helpers\GConst;
$donor = GConst::CIRCLE_DONOR;
$advisor = GConst::CIRCLE_ADVISOR;
$staff = GConst::CIRCLE_STAFF;
$public = GConst::CIRCLE_PUBLIC;
?>
@extends ('agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => 'Notification', 'hcXlWidth' => '12'])
    <div class="container history-container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <form method="POST" accept-charset="UTF-8" id="bell-notification-form">
                            @csrf
                            <div class="row">
                                <div id="id_change_form_layout" class="col-sm-11">
                                    <div class="form-group row {{ $errors->has('notification') ? 'has-error' : '' }}">
                                        <label for="notification" class="col-sm-3 col-form-label text-right pr-0">
                                            Notification <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="4" id="notification" 
                                            placeholder="Write Notification Message.." name="notification" 
                                            cols="50"></textarea>
                                            <small id="notification_err" style="color: red;"></small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="anonymous" class="col-sm-3 col-form-label text-right pr-0">
                                                Send Notification To <span class="text-danger">*</span>
                                            </label>

                                            <div class="col-sm-9" style="padding: 6px 8px;">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <input class="form-control2 checkbox-1x ml-1" 
                                                        id="id_{{ $donor }}" name="notification_sent_to[]" 
                                                        type="checkbox" value="{{ $donor }}"> &nbsp; {{ ucfirst($donor) }}
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <input class="form-control2 checkbox-1x ml-1" 
                                                        id="id_{{ $advisor }}" name="notification_sent_to[]" 
                                                        type="checkbox" value="{{ $advisor }}"> &nbsp; {{ ucfirst($advisor) }}
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <input class="form-control2 checkbox-1x ml-1" 
                                                        id="id_{{ $staff }}" name="notification_sent_to[]" 
                                                        type="checkbox" value="{{ $staff }}"> &nbsp; {{ ucfirst($staff) }}
                                                    </div>

                                                    <div class="col-sm-3 hide">
                                                        <input class="form-control2 checkbox-1x ml-1" 
                                                        id="id_{{ $public }}" name="notification_sent_to[]" 
                                                        type="checkbox" value="{{ $public }}"> &nbsp; {{ ucfirst($public) }}
                                                    </div>

                                                    <small id="notification_sent_to_err" style="color: red;"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Send To *</label>
                                        <div class="col-sm-9">
                                            <select multiple name="send_to" id="send_to" class="form-control">

                                                <option value="0">Select Circle</option>

                                                <option value="{{ \App\Helpers\GConst::CIRCLE_DONOR }}">{{ ucfirst(\App\Helpers\GConst::CIRCLE_DONOR) }}</option>

                                                <option value="{{ \App\Helpers\GConst::CIRCLE_ADVISOR }}">{{ ucfirst(\App\Helpers\GConst::CIRCLE_ADVISOR) }}</option>

                                                <option value="{{ \App\Helpers\GConst::CIRCLE_STAFF }}">{{ ucfirst(\App\Helpers\GConst::CIRCLE_STAFF) }}</option>

                                                <option value="{{ \App\Helpers\GConst::CIRCLE_PUBLIC }}">{{ ucfirst(\App\Helpers\GConst::CIRCLE_PUBLIC) }}</option>
                                            </select>
                                            <small id="send_to_err" style="color: red;display: none;">This field is required!</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="donor" class="col-sm-3 col-form-label text-right pr-0"></label>
                                        <div class="col-sm-4">
                                            <input name="save" id="id_save_btn" class="btn btn-accent w100" 
                                            type="button" value="Send" onclick="sendBellNotification();">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="donor" class="col-sm-3 col-form-label text-right pr-0"></label>
                                        <div>
                                            <span id="notification_msg" style="color: green;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function sendBellNotification() {

    const $btn = $('#id_save_btn');
    const $msg = $('#notification_msg');

    // Reset errors
    $('#notification_err').text('');
    $('#notification_sent_to_err').text('');
    $msg.text('');

    const notification = $('#notification').val().trim();
    const selectedUsers = $('input[name="notification_sent_to[]"]:checked');

    let hasError = false;

    // Validation
    if (!notification) {
        $('#notification_err').text('Notification message is required!');
        hasError = true;
    }

    if (selectedUsers.length === 0) {
        $('#notification_sent_to_err').text('At least one circle is required!');
        hasError = true;
    }

    if (hasError) return;

    // Disable button
    $btn.prop('disabled', true).val('Please Wait...');

    const formData = new FormData($('#bell-notification-form')[0]);

    $.ajax({
        url: "{{ route('agency-send-manual-notification') }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json'
    })
    .done(function (response) {

        $btn.prop('disabled', false).val('Send');

        $msg.text(response.msg).css('color', response.color || 'green');

        if (response.status === 'success') {
            setTimeout(() => {
                window.location.href = "{{ route('agency-notifications') }}";
            }, 2000);
        }

    })
    .fail(function () {

        $btn.prop('disabled', false).val('Send');

        $msg.text('Something went wrong. Please try again.')
            .css('color', 'red');
    });
}
</script>
@endsection