<?php
$pageTitle = "Create Task";
?>
@extends ('agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <form method="POST" action="{{ route('storeTask') }}" accept-charset="UTF-8" id="grant-form">
                            @csrf
                            <div class="row">
                                <div id="id_change_form_layout" class="col-sm-11">
                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
                                        <div class="col-sm-9">
                                            <select id="id_fund_id" class="form-control" name="fund_id" onchange = "getDonorEmails();">
                                                <option value="0">Select Fund</option>
                                                @foreach($contactFunds as $fund => $val)

                                                    <option value="{{ $fund }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Task Type</label>
                                        <div class="col-sm-9">
                                            <select id="task_type_id" class="form-control" name="task_type_id" onchange="toggleEmailCheckbox()">
                                                <option value="0">Select Task Type</option>
                                                <option value="Event">Event</option>
                                                <option value="Meeting">Meeting</option>
                                                <option value="Notes">Notes</option>
                                                <option value="Raise Cash">Raise Cash</option>
                                                <option value="Rebalace Portfolio">Rebalace Portfolio</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Subject</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="subject" 
                                            placeholder="Subject.." name="subject" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Description</label>
                                        <div class="col-sm-9">
                                                <textarea class="form-control" rows="4" id="description" placeholder="Description.." name="description" cols="50"></textarea>
                                            <script>
                                                ClassicEditor
                                                    .create( document.querySelector( '#description' ) )
                                                    .catch( error => {
                                                        console.error( error );
                                                    } );
                                            </script>                                            
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="start_date" placeholder="Start Date" name="start_date">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">End Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="end_date" placeholder="End Date" name="end_date">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Priority</label>
                                        <div class="col-sm-9">
                                            <select id="task_priority" class="form-control" name="task_priority" onchange="">
                                                <option value="0">Select Task Priority</option>
                                                <option value="Low">Low</option>
                                                <option value="Normal">Normal</option>
                                                <option value="High">High</option>
                                                <option value="Urgent">Urgent</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-md-3 col-md-9 xs-mt-2">
                                            <div class="form-check form-check-inline" id="emailCheckboxContainer" disabled>
                                                <input class="form-check-input checkbox-1x mr-2" id="is_send_mail" name="is_send_mail" type="checkbox" onclick="toggleDonorDataDropdown()">
                                                <label for="is_send_mail" class="form-check-label font-small fw600">Send Mail</label>
                                            </div>
                                        </div>
                                    </div>
                                
                                    {{-- DONOR DROPDOWN FOR SENDING MAIL --}}
                                    <div class="form-group row" id="staticDataDropdown" style="display: none;">
                                        <div class="offset-md-3 col-md-9 xs-mt-2">
                                            <label for="staticData" class="text-right">Donor Contact</label>
                                            <div id="donor_list_div">
                                                {{-- Donor emails and name comes on this part --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-5 col-md-4">
                                    <input name="save" id="id_save_btn" class="btn btn-accent btn-sm w100" type="submit" value="Submit">
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="" class="cancel" onclick="">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
<script>
    function getDonorEmails() {
        var selectedValue = $("#id_fund_id").val();

        $.ajax({
            type: 'GET',
            url: "/m/agency/services/donor-email",
            data: { 'donorId': selectedValue },
            success: function (response) {
                // Handle the JSON response and construct HTML on the client side
                var donorEmails = response.donorEmails;
                var html = '';

                if (donorEmails.length > 0) {
                    donorEmails.forEach(function (email) {
                        html += '<div class="form-check">' +
                            '<input class="form-check-input checkbox-1x mr-2" type="checkbox" id="option'+email.email_address_id+'" name="static_data[]" value="' + email.contact_id + '">' +
                            '<label class="form-check-label" for="option1">' + email.first_name +" "+ email.last_name +'</label>' +
                            '<input type="hidden" name="email_address[]" value="'+email.email_address+'" id="email_address'+email.email_address_id+'">' +
                            '</div>';
                    });
                }
                $("#donor_list_div").html(html);
            }
        });
    }

    function getemail1(email_address_id, email_address){
        var wasChecked = $("#option"+email_address_id).prop('checked');
        if(wasChecked == true){
            $("#email_address"+email_address_id).val(email_address);
        }else{
            $("#email_address"+email_address_id).val("");
        }   
    }

    function toggleEmailCheckbox() {
        var taskTypeDropdown = document.getElementById("task_type_id");
        var emailCheckbox = document.getElementById("is_send_mail");
        var emailLabel = document.querySelector("label[for='is_send_mail']");

        // Check if the selected task type is "Event"
        if (taskTypeDropdown.value === "Event") {
            emailCheckbox.style.display = "block"; // Show the checkbox
            emailLabel.style.display = "block";     // Show the label
        } else {
            emailCheckbox.style.display = "none";  // Hide the checkbox
            emailLabel.style.display = "none";      // Hide the label
        }
    }


    function toggleDonorDataDropdown() {
        var emailCheckbox = document.getElementById("is_send_mail");
        var staticDataDropdown = document.getElementById("staticDataDropdown");

        // Check if the checkbox is checked
        if (emailCheckbox.checked) {
            staticDataDropdown.style.display = "block"; // Show the options
        } else {
            staticDataDropdown.style.display = "none";  // Hide the options
        }
    }

    // Add an event listener to the email checkbox to call the function when it's changed
    var emailCheckbox = document.getElementById("is_send_mail");
    emailCheckbox.addEventListener("change", toggleDonorDataDropdown);

    // Call the function initially to set the initial state based on the checkbox value
    toggleDonorDataDropdown();
</script>







