<?php
$intervals = [
        'one' => 'One-time',
        'monthly' => 'Every month',
        'bi-monthly' => 'Every other month',
        'quarterly' => 'Every 3 months',
        'half-yearly' => 'Every 6 months',
        'yearly' => 'Every 12 months',
];
$countries = \App\Models\Country::getListUSAOnly(); // getList();
$states = \App\Models\State::getCodeListUSA();

$tomorrow = new \DateTime();
$tomorrow->modify('+1 day');
$minDate = $tomorrow->format('Y-m-d');
?>

@if(\Illuminate\Support\Facades\App::environment('prod'))
    <script type="text/javascript" src="https://js.authorize.net/v3/AcceptUI.js" charset="utf-8"> </script>
@else
    <script type="text/javascript" src="https://jstest.authorize.net/v3/AcceptUI.js" charset="utf-8"></script>
@endif

<script type="text/javascript" src="/ma/javascripts/donation.js" charset="utf-8"></script>

<a href="javascript:void(0);" id="id_btn_donate" class="btn btn-theme btn-sm btn-donate hide" onclick="return jsDonation.onShowDonationForm()">Donate</a>

<form name="donation-form" id="id_donation_form" method="POST" action="/donation" style="display: none2">

    <div class="donation-form">

        {{ csrf_field() }}        {{-- authorize.net fields --}}
        <input type="hidden" name="dataValue" id="id_data_value" />
        <input type="hidden" name="dataDescriptor" id="id_data_descriptor" />

        <h2 class="page-subtitle">
            DONATE BY CREDIT CARD
        </h2>

        <p>Making a donation is quick, safe and secure.</p>

        <p>If you encounter any problems with our secure online credit card donation, please call us at 973-984-8200</p>

        <p id="id_response_error" style="display: none">
            some error
        </p>

        <div id="id_error_list" style="display: none">
            <p>Please fill valid values in the underlined fields
                <a href="javascript:void(0);" onclick="jsDonation.clearFieldErrors();"><i class="far fa-times-circle"></i></a>
            </p>
            <ul class="list"></ul>
        </div>

        {{-- search --}}
        <div class="form-group row">
            <div class="offset-md-3 col-md-9">
                <div class="form-check form-check-inline" style="display: none">
                    <input class="form-check-input" type="radio" name="search_option" id="inlineRadioAll" value="all"
                           onclick="jsDonation.onChangeSearch(this);">
                    <label class="form-check-label font-small" for="inlineRadioAll">Search all</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="search_option" id="inlineRadioOrgs" value="orgs"
                           checked="checked" onclick="jsDonation.onChangeSearch(this);">
                    <label class="form-check-label font-small" for="inlineRadioOrgs">Search Organization</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="search_option" id="inlineRadioFunds" value="funds" onclick="jsDonation.onChangeSearch(this);">
                    <label class="form-check-label font-small" for="inlineRadioFunds">Search Fund</label>
                </div>
            </div>
        </div>

        {{-- typeahead - search all --}}
        <div id="id_fund_org_typeahead" class="form-group row" style="display: none">
            <label for="any_name" class="col-sm-3 col-form-label text-right">Name</label>
            <div class="col-sm-8">
                <input class="form-control typeahead2"
                       placeholder="Enter Fund/Organization name"
                       name="fund_org_name"
                       type="text"
                       id="fund_org_name"
                       autocomplete="off">
                <div id="id-searched-fund-org-info"></div>
            </div>
        </div>

        {{-- typeahead - search organizations #4595 --}}
        <input type="hidden" name="organization_id" id="id_organization" value="">
        <div id="id_org_typeahead" class="form-group row">
            <label for="id_organization_name" class="col-sm-3 col-form-label text-right">Name</label>
            <div class="col-sm-8">
                <input class="form-control typeahead"
                       placeholder="Enter Organization Name"
                       name="organization_name"
                       type="text"
                       id="id_organization_name"
                       autocomplete="off">
                <div id="id-searched-org-address"></div>
            </div>
        </div>

        {{-- typeahead - search funds --}}
        <input type="hidden" name="fund_id" id="id_fund">
        <div id="id_fund_typeahead" class="form-group row" style="display: none">
            <label for="id_fund_name" class="col-sm-3 col-form-label text-right">Name</label>
            <div class="col-sm-8">
                <input class="form-control typeahead"
                       placeholder="Enter Fund Name"
                       name="fund_name"
                       type="text"
                       id="id_fund_name"
                       autocomplete="off">
                <div id="id-searched-fund-info"></div>
            </div>
        </div>

        {{-- amount --}}
        <div class="form-group row">
            {!! Form::label('amount', 'Amount ($)', ['class' => 'col-sm-3 col-form-label text-right']) !!}
            <div class="col-sm-8">
                {!! Form::number('amount', null, ['id' => 'id_floating_amount', 'placeholder' => '100 or more', 'class' => 'form-control',
                'pattern' => '[0-9]+([\.,][0-9]+)?', 'step' => "0.01", 'step2' => 'any']) !!}
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('interval', 'Interval', ['class' => 'col-sm-3 col-form-label text-right']) !!}
            <div class="col-sm-6 col-md-4">
                {!! Form::select('interval', $intervals, null, ['class' => 'form-control', 'onchange'=> "jsDonation.onChangeInterval(this)"]) !!}
            </div>
        </div>

        <div id="id_intervals" style="display: none;">
            <div class="form-group row">
                {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-3 col-form-label text-right']) !!}
                <div class="col-sm-6 col-md-4">
                    {!! Form::date('start_date', null, ['id' => 'id_start_date', 'min' => $minDate, 'class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-md-3 col-md-9 xs-mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="no_end" id="id_end" checked="checked" onclick="jsDonation.onEndDate(this)">
                        <label class="form-check-label font-small" for="id_end">No End Date (ongoing) </label>
                    </div>
                </div>
            </div>

            <div id="id_end_date_view" style="display: none;">
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

        {{-- dedicate to --}}
        <div class="form-group row">
            <div class="offset-md-3 col-md-9 xs-mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="dedicated" id="id_dedicated_text" onclick="jsDonation.onDedicate(this)">
                    <label class="form-check-label font-small" for="id_dedicated_text">Dedicate this gift to someone</label>
                </div>
            </div>
        </div>

        <div id="id_dedicated_to" style="display: none">
            <div class="form-group row">
                <div class="offset-md-3 col-md-8 xs-mt-2">
                    <input id="id_dedicated_to_name" name="dedicated_to_name" type="text" class="form-control" placeholder="Full name">
                </div>
            </div>
        </div>

        {{-- notify --}}
        <div class="form-group row">
            <div class="offset-md-3 col-md-9 xs-mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="notify" id="id_inform_text" onclick="jsDonation.onInformTo(this)">
                    <label class="form-check-label font-small" for="id_inform_text">Notify the following person of this gift</label>
                </div>
            </div>
        </div>

        <div id="id_inform_to" style="display: none">
            <div class="form-group row account-name">
                <label for="id_notify_fname" class="col-md-3 col-form-label text-right pr-0">Name</label>
                <div class="col-6 col-md-4">
                    <input id="id_notify_fname" maxlength="32" name="notify_fname" type="text" class="form-control" placeholder="First name">
                </div>

                <div class="col-6 col-md-4 pl-0">
                    <input id="id_notify_lname" maxlength="32" name="notify_lname" type="text" class="form-control" placeholder="Last name">
                </div>
            </div>

            <div class="form-group row">
                <label for="id_notify_address_one" class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
                <div class="col-md-9">
                    <input id="id_notify_address_one" maxlength="50" name="notify_address_one" type="text" class="form-control" placeholder="">
                </div>
            </div>

            <div class="form-group row">
                <label for="id_notify_address_two" class="col-md-3 col-form-label text-right pr-0">Address Line 2</label>
                <div class="col-md-9">
                    <input id="id_notify_address_two" maxlength="50" name="notify_address_two" type="text" class="form-control" placeholder="Optional">
                </div>
            </div>

            <div class="form-group row">
                <label for="id_notify_city" class="col-md-3 col-form-label text-right pr-0">City</label>
                <div class="col-md-4">
                    <input id="id_notify_city" maxlength="32" name="notify_city" type="text" class="form-control" placeholder="">
                </div>

                <label for="id_notify_state" class="col-md-1 col-form-label text-right pr-0">State</label>
                <div class="col-md-4">
                    {{--<input id="id_notify_state" maxlength="32" name="notify_state" type="text" class="form-control" placeholder="">--}}
                    {!! Form::select('notify_state', $states, '', ['id' => 'id_notify_state', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group row">
                <label for="id_notify_country" class="col-md-3 col-form-label text-right pr-0">Country</label>
                <div class="col-md-4">
                    {{--<input maxlength="32" name="notify_country" type="text" class="form-control" placeholder="">--}}
                    {!! Form::select('notify_country', $countries, 'USA', ['id' => 'id_notify_country', 'class' => 'form-control', 'disabled2']) !!}
                </div>

                <label for="id_notify_zip" class="col-md-1 col-form-label text-right pr-0">ZIP</label>
                <div class="col-md-4">
                    <input id="id_notify_zip" maxlength="16" name="notify_zip" type="text" class="form-control" placeholder="">
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h5 class="form-group-title">Personal Information</h5>
            </div>
        </div>

        <div class="form-group row account-name">
            <label for="id_fname" class="col-md-3 col-form-label text-right pr-0">Name</label>
            <div class="col-6 col-md-4">
                <input id="id_fname" maxlength="32" name="guest_fname" type="text" class="form-control" placeholder="First name">
            </div>

            <div class="col-6 col-md-4 pl-0">
                <input id="id_lname" maxlength="32" name="guest_lname" type="text" class="form-control" placeholder="Last name">
            </div>
        </div>

        <div class="form-group row">
            <label for="id_email" class="col-md-3 col-form-label text-right pr-0">Email</label>
            <div class="col-md-8">
                <input id="id_email" maxlength="128" name="guest_email" type="email" class="form-control" placeholder="Your email address" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="id_phone" class="col-md-3 col-form-label text-right pr-0">Phone #</label>
            <div class="col-6 col-md-4">
                <input id="id_phone" maxlength="16" name="guest_phone" type="text" class="form-control" placeholder="Optional">
            </div>
        </div>


        <div class="form-group row">
            <div class="offset-md-3 col-md-9 xs-mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="from_org" id="id_from_org" onclick="jsDonation.onBehalfOf(this)">
                    <label class="form-check-label font-small" for="id_from_org">This donation is on behalf of a company or organization.</label>
                </div>
            </div>
        </div>

        <div id="id_on_behalf_of" style="display: none">
            <div class="form-group row">
                <div class="offset-md-3 col-md-8 xs-mt-2">
                    <input id="id_from_org_name" maxlength="128" name="donor_org_name" type="text" class="form-control" placeholder="Enter Company or Organization name">
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h5 class="form-group-title">Address</h5>
            </div>
        </div>

        <div class="form-group row">
            <label for="id_address_one" class="col-md-3 col-form-label text-right pr-0">Address Line 1</label>
            <div class="col-md-9">
                <input id="id_address_one" maxlength="50" name="guest_address_one" type="text" class="form-control" placeholder="">
            </div>
        </div>

        <div class="form-group row">
            <label for="id_address_two" class="col-md-3 col-form-label text-right pr-0">Address Line 2</label>
            <div class="col-md-9">
                <input id="id_address_two" maxlength="50" name="guest_address_two" type="text" class="form-control" placeholder="Optional">
            </div>
        </div>

        <div class="form-group row">
            <label for="id_city" class="col-md-3 col-form-label text-right pr-0">City</label>
            <div class="col-md-4">
                <input id="id_city" maxlength="32" name="guest_city" type="text" class="form-control" placeholder="">
            </div>

            <label for="id_state" class="col-md-1 col-form-label text-right pr-0">State</label>
            <div class="col-md-4">
                {{--<input id="id_state" maxlength="32" name="guest_state" type="text" class="form-control" placeholder="">--}}
                {!! Form::select('guest_state', $states, '', ['id' => 'id_state', 'class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group row">
            <label for="id_country" class="col-md-3 col-form-label text-right pr-0">Country</label>
            <div class="col-md-4">
                {{--<input id="id_country" maxlength="32" name="guest_country" type="text" class="form-control" placeholder="">--}}
                {!! Form::select('guest_country', $countries, 'USA', ['id' => 'id_country', 'class' => 'form-control', 'disabled2']) !!}
            </div>

            <label for="id_zip" class="col-md-1 col-form-label text-right pr-0">ZIP</label>
            <div class="col-md-4">
                <input id="id_zip" maxlength="16" name="guest_zip" type="text" class="form-control" placeholder="">
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="offset-md-3 col-sm-4">
                <a href="javascript:void(0);" onclick="jsDonation.onContinue()" class="btn btn-accent w100">Continue</a>
            </div>
        </div>

    </div>

</form>

<hr>
<div class="text-right">
    <a href="{{ url()->previous() }}" class="cancel" onclick="">Cancel</a>
</div>

<div style="margin: 0; width: 1px;  height: 1px; overflow: hidden;">
    <button type="button"
            class="AcceptUI btn btn-theme"
            data-billingAddressOptions='{"show":true, "required":false}'
            data-apiLoginID="{{env('MERCHANT_LOGIN_ID')}}"
            data-clientKey="{{env('MERCHANT_PUBLIC_CLIENT_KEY')}}"
            data-acceptUIFormBtnTxt="Submit"
            data-paymentOptions='{"showCreditCard": true, "showBankAccount": false}'
            data-acceptUIFormHeaderTxt="Card Information"
            data-responseHandler="donationResponseHandler">Continue1
    </button>
</div>


<script>
    $(function(){
        jsDonation.init();
    });
</script>


