{{--CCT Specific--}}

<?php
$dedicationTypes = [
        '' => 'None',
        'In Honor of' => 'In Honor of',
        'In Memory of' => 'In Memory of'
];

$payoutDates = \App\Helpers\GnUtils::selectableUpcomingTuesdaysAndThursdaysCCT();
$displayNoEnd = $model->isRecurring() ? 'inherit' : 'none';
$noEnd = $model->no_end == 'Y';
$displayEndDate = $model->no_end == 'Y' ? 'none' : 'inherit';
$displayOboName = $model['show_advisor_name'] ? 'inherit' : 'none';
?>

<style>
    .form-check-inline { padding-left: 4px; }
</style>

{{-- is this a closing grant --}}
<div class="form-group row">
    <div class="offset-md-3 col-md-9 xs-mt-2">
        <div class="form-check form-check-inline">
            {!! Form::checkbox('is_closing_grant', null, null, ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'is_closing_grant']) !!}
            {!! Form::label('is_closing_grant', 'Make this a Fund Closing Grant', ['class' => 'form-check-label font-small fw600']) !!}
        </div>
    </div>
</div>

<div id="id_closing_info" style="display: none; margin-top: -12px;">
    <div class="form-group row">
        <div class="offset-md-3 col-md-8 xs-mt-2">
            This grant will be in the amount of your total fund balance minus amount of fees, and will close out your selected fund
        </div>
    </div>
</div>


<div id="id_frequency">
    <div class="form-group row">
        {!! Form::label('frequency', \App\Models\GrantForm::frequencyLabel(), ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9">
            {!! Form::select('frequency', $frequencies, null, ['class' => 'form-control', 'onchange'=> "onChangeFrequency(this)"]) !!}

            @if(\App\Models\ClientInfo::isHGA())
                <span class="from-footer">Please note recurring grant distributions will occur on the second business day unless otherwise directed.</span>
            @endif
        </div>
    </div>

    <div id="id_ends" style="display: {{$displayNoEnd}};">
        <div class="form-group row">
            <div class="offset-md-3 col-md-9 xs-mt-2">
                <div class="form-check form-check-inline">
                    {!! Form::checkbox('no_end', null, $noEnd, ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'no_end', "onclick" => "onEndDate(this)"]) !!}
                    {!! Form::label('no_end', 'No End Date (ongoing) ', ['class' => 'form-check-label font-small fw600', 'for' => 'no_end']) !!}
                </div>
            </div>
        </div>

        <div id="id_end_date_view" style="display: {{$displayEndDate}};">
            {{-- occurrences --}}
            <div class="form-group row" style="align-items: baseline;">
                <label for="booking_date" class="col-sm-3 col-form-label text-right">
                    Ends after
                </label>
                <div class="col-sm-6 col-md-4">
                    {!! Form::number('occurrences', null, ['id' => 'id_occurrences', 'placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="col-3 pl-0">
                    occurrences
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('requested_disbursement_date', 'Payout Date', ['class' => 'col-sm-3 col-form-label text-right']) !!}
    <div class="col-sm-6 col-md-4">
        {!! Form::select('requested_disbursement_date', $payoutDates, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('purpose_type', 'Grant Purpose', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('grant_purpose', null, ['class' => 'form-control', 'rows' => 2, 'id' => 'id_grant_purpose', 'placeholder' => 'Special grant purpose']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('notes', 'Instructions', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => 'Instructions to CCT staff']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('dedication_type', 'Grant Dedication', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::select('dedication_type', $dedicationTypes, $model->dedication_type, ['class' => 'form-control', 'onchange'=> "onChangeDedication(this)"]) !!}
    </div>
</div>

{{-- This become visible when 'grant dedication' is selected --}}
<div id="id_grant_dedication_container" style="display: none;">
    <div class="form-group row">
        <div class="offset-md-3  col-sm-9">
            {!! Form::textarea('grant_dedication', null, ['class' => 'form-control', 'rows' => 2, 'id' => 'id_grant_dedication', 'placeholder' => 'Grant dedication']) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-md-3 col-md-9 xs-mt-2">
            <p>If you would like the grantee to send an acknowledgement of this honorary/memorial grant, please include their name and contact information below.</p>
            <div class="form-check form-check-inline">
                {!! Form::checkbox('notify', null, null, ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'id_notify']) !!}
                {!! Form::label('id_notify', 'Send an acknowledgement', ['class' => 'form-check-label font-small fw600']) !!}
            </div>
        </div>
    </div>

    <div class="form-group row" id="id_notification_info_box" style="display: none;">
        <div class="offset-md-3  col-sm-9">
            {!! Form::textarea('notification_info', null, ['class' => 'form-control', 'rows' => 2, 'id' => 'id_notification_info', 'placeholder' => 'Recipient Email or Address']) !!}
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="offset-md-3  col-sm-9">
        <h4 class="mt-3">Award Letter Options</h4>
        <span>The grant award letter tells the grantee how you would like to be acknowledged for the grant.
            You will be able to preview the award letter before you submit the grant.</span>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('anonymous', 'Donor Identity', ['class' => 'col-sm-3 col-form-label text-right pr-0', 'for' => 'id_anonymous']) !!}
    <div class="col-sm-9" style="padding: 6px 8px;">
        {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x ml-1', 'id' => 'id_anonymous']) !!}
        <label class="form-check-label" for="id_anonymous">&nbsp; I wish to remain anonymous</label>
    </div>
</div>

<div id="id_non_anonymous_info" style="display: none;">
    <div class="form-group row">
        <div class="offset-md-3 col-md-9 xs-mt-2">
            <div class="form-check form-check-inline">
                {!! Form::checkbox('show_fund_name', null, null, ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'id_show_fund_name']) !!}
                {!! Form::label('show_fund_name', 'Show Fund Name', ['class' => 'form-check-label font-small fw600', 'for' => 'id_show_fund_name']) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-md-3 col-md-9 xs-mt-2">
            <div class="form-check form-check-inline">
                {!! Form::checkbox('show_advisor_name', null, null, ['class' => 'form-check-input checkbox-1x mr-2', 'id' => 'show_advisor_name', "onclick" => "onShowAdvisorName(this)"]) !!}
                {!! Form::label('show_advisor_name', 'Show Advisor Name', ['class' => 'form-check-label font-small fw600', 'for' => 'show_advisor_name']) !!}
            </div>
        </div>
    </div>

    <div id="id_obo_name" style="display: {{$displayOboName}};">
        <div class="form-group row" id="id_from_contact_box"></div>

        @if($model->from_contact_id)
            <div id="id_from_contact_info_box">
                @include(\App\Models\ClientInfo::clientViewFor('grants._selected_from_contact', 'donor.'))
            </div>
        @else
            <div id="id_from_contact_info_box" style="display: none"></div>
        @endif

    </div>

</div>



<script>
    function onShowAdvisorName(item) {
        var oboView = $('#id_obo_name');
        if (item.checked) {
            oboView.show(500);
            $('#from_contact_id').prop('required', true);
        } else {
            oboView.hide(500);
            $('#from_contact_id').prop('required', false);
        }
    }

    // 1. account closing grant
    $(function() {
        var itemClosing = document.getElementById('is_closing_grant');
        if (itemClosing.checked){
            onClosingGrantOn();
        } else {
            onClosingGrantOff();
        }
        itemClosing.addEventListener('change', function(event) {
            var closing = $('#id_closing_info');
            var frequency = $('#id_frequency');
            if (event.currentTarget.checked) {
                onClosingGrantOn();
            } else {
                onClosingGrantOff();
            }
        });
        function onClosingGrantOn() {
            $('#id_frequency').hide(600);
            $('#id_closing_info').show(300);
            setClosingGrant(true);
        }
        function onClosingGrantOff() {
            $('#id_closing_info').hide(600);
            $('#id_frequency').show(300);
            setClosingGrant(false);
        }
    });

    // 2. anonymous
    $(function() {
        var itemAnonymous = document.getElementById('id_anonymous');
        itemAnonymous.checked ? onHideSelectFromDonor() : onShowSelectFromDonor();
        itemAnonymous.addEventListener('change', function (event) {
            event.currentTarget.checked ? onHideSelectFromDonor() : onShowSelectFromDonor();
        });
    });

    function onShowSelectFromDonor() {
        $('#id_non_anonymous_info').show(400);
        if ($("#show_advisor_name").is(':checked')) {
            $('#from_contact_id').prop('required', true);
        }
    }
    function onHideSelectFromDonor() {
        $('#id_non_anonymous_info').hide(400);
        if (($("#show_advisor_name").is(':checked'))) {
            $('#from_contact_id').prop('required', false);
        }
    }

    // 3. grant dedication & purpose
    $(function(){
        // handle select option
        var selectedDedication = $('#dedication_type').find(":selected").val();
        if (selectedDedication && selectedDedication != '') {
            $('#id_grant_dedication_container').show(400);
            $('#id_grant_dedication').prop('required', true);
        }
    });
    function onChangeDedication(item) {
        var container = $('#id_grant_dedication_container');
        var containerInput = $('#id_grant_dedication');
        if (item.value == null || item.value == '') {
            container.hide(400);
            containerInput.prop('required', false);
        } else {
            container.show(400);
            containerInput.prop('required', true);
        }
    }

    // 4. notification
    $(function() {
        var itemNotify = document.getElementById('id_notify');
        itemNotify.checked ? onShowNotificationInfo() : onHideNotificationInfo();
        itemNotify.addEventListener('change', function (event) {
            event.currentTarget.checked ? onShowNotificationInfo() : onHideNotificationInfo();
        });
    });


    function onShowNotificationInfo() {
        $('#id_notification_info_box').show(400);
        $('#id_notification_info').prop('required', true);
    }
    function onHideNotificationInfo() {
        $('#id_notification_info_box').hide(400);
        $('#id_notification_info').prop('required', false);
    }


    // change frequency & end date
    function onChangeFrequency(item) {
        var endsView = $('#id_ends');
        item.value == 'once' ? endsView.hide(400) : endsView.show(400);
    }

    function onEndDate(item) {
        var occurrencesView = $('#id_end_date_view');
        item.checked ? occurrencesView.hide(400) : occurrencesView.show(400);
    }

    var varFromContactId = {!! json_encode($fromContactId) !!};

    $(function(){
        // 2. fund id
        var itemFundId = $('#id_fund_id');

        var fundId = itemFundId.val();
        onFundIdChanged(fundId, varFromContactId);

        itemFundId.change(function() {
            fundId = $(this).val();
            onFundIdChanged(fundId, null);
        });
    });

    function onFundIdChanged(fundId, fromContactId){

        var url = '/m/ajax/make-a-grant/select-grant-from?fund_id=' + fundId;
        if (fromContactId) url += '&from_contact_id=' + fromContactId;

        $("body").css("cursor", "progress");

        $.ajax({
            url: url,
            dataType: 'json',
            method: 'get'
        }).done(function (data) {
            if (!data || data.status != 200) {
                if (data.mesg) showAlert('Error', data.mesg);
                else showAlert('Error', "Your request could not be processed!");
            } else {
                $('#id_from_contact_box').html(data.html);

                var itemAnonymous = document.getElementById('id_anonymous');
                if (itemAnonymous.checked) {
                    $('#from_contact_id').prop('required', false);
                } else {
                    $('#from_contact_id').prop('required', true);
                }

                if (!fromContactId) onFromContactChanged(null);
            }
            $("body").css("cursor", "default");
        }).fail(function(){
            showAlert('Failed', "Your request could not be processed!");
            $("body").css("cursor", "default");
        });
    }

    $('#from_contact_id').change(function(){
        alert($(this).val());
    });

    function onFromContactChanged(contactId){
        console.log("from contact id changed!");

        var contactInfoView = $('#id_from_contact_info_box');
        contactInfoView.hide(400);

        // null, undefined, NaN, empty string (""), 0, false
        if (!contactId) {
            console.log("null from contact id!");
            return false;
        }
        var url = '/m/ajax/make-a-grant/selected-grant-from?from_contact_id=' + contactId;
        $("body").css("cursor", "progress");

        $.ajax({
            url: url,
            dataType: 'json',
            method: 'get'
        }).done(function (data) {
            if (!data || data.status != 200) {
                if (data.mesg) showAlert('Error', data.mesg);
                else showAlert('Error', "Your request could not be processed!");
            } else {
                contactInfoView.html(data.html);
                contactInfoView.show(400);
            }
            $("body").css("cursor", "default");
        }).fail(function(){
            showAlert('Failed', "Your request could not be processed!");
            $("body").css("cursor", "default");
        });
    }

</script>
