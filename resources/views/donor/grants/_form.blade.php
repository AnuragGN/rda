<?php
/*
 * Form is used for
 *  - Repeat Grant
 *  - Create & Edit
 * In each case, updated org-name should be available in grant object
 *  - Take care Create and Edit for Ad-hoc organizations
 */
$states = \App\Models\State::getCodeListUSA();
$defaultState = \App\Models\State::getDefaultStateCode();
$countries = \App\Models\Country::getListUSAOnly(); // getList();
$minGrantAmount = \App\Models\ClientConfig::value('MIN_GRANT_AMOUNT');
$frequencies = \App\Helpers\Data::selectableGrantingFrequencies();

$fromContactId = $model->from_contact_id;

$placeholder = $minGrantAmount > 1 ? $minGrantAmount . ' or more' : '';

$today = new \DateTime();
$minDate = $today->format('Y-m-d');

$clientId = \App\Models\ClientInfo::client();
?>

<script type="text/javascript" src="/ma/javascripts/grant-form.js" charset="utf-8"></script>

@include('errors.form-errors')

{!!  Form::hidden('cart_id', null, []) !!}
{!!  Form::hidden('contact_id', null, []) !!}
{!!  Form::hidden('organization_id', null, ['id' => 'id_organization']) !!}

@if ($model->cart_id || $model->organization_id)
    @include('donor.grants.organization-address')
@endif

<div class="form-group row">
    {!! Form::label('fund', 'Fund Name', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::select('fund_id', $funds, null, ['id' => 'id_fund_id', 'class' => 'form-control']) !!}
    </div>
</div>

@if (!$model->cart_id && !$model->organization_id)
    {{-- OrganizationTypeAhead --}}
    <div id="id_org_typeahead" class="form-group row">
        {!! Form::label('organization_name', 'Organization Name', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9">
            <div class="org-group">
                {!! Form::text('organization_name', null, ['id' => 'id_org_name_typeahead', 'class' => 'form-control typeahead', 'required' => 'required', 'placeholder' => '']) !!}
                <a href="javascript:void(0)" id="id_add_org_btn" onclick="grantForm.toggleOrgAddress()" style="display: none">
                    <i class="fas fa-plus-circle"></i>
                    <i class="fas fa-minus-circle i-dull"></i>

                </a>
            </div>

            <div id="id-searched-org-address"></div>
        </div>
    </div>
@endif

<div id="id_org_info_container" style="display: none">
    {{-- place holder--}}
</div>

@if(\App\Models\ClientInfo::isCCT())

    <div class="form-group row">
        {!! Form::label('contact_name', 'Contact Person', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9 col-md-4">
            {!! Form::text('contact_name', null, ['class' => 'form-control', 'id'=>'id_contact_name_out', 'maxlength'=>'32' ]) !!}
        </div>
        <div class="col-12 d-md-none" style="margin-bottom: 0.75rem;"></div>
        {!! Form::label('title', 'Title', ['class' => 'col-sm-3 col-md-2 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9 col-md-3">
            {!! Form::text('org_contact_title', null, ['class' => 'form-control', 'id'=>'id_contact_title_out', 'maxlength'=>'32' ]) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-sm-3 col-form-label text-right pr-0">Email</label>
        <div class="col-sm-9 col-md-4">
            {!! Form::email('email', null, ['class' => 'form-control', 'id'=>'id_contact_email_out', 'maxlength'=>'60', 'placeholder'=>'contact@example.com' ]) !!}
        </div>
        <div class="col-12 d-md-none" style="margin-bottom: 0.75rem;"></div>
        {!! Form::label('phone', 'Phone', ['class' => 'col-sm-3 col-md-2 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9 col-md-3">
            {!! Form::text('phone', null, ['class' => 'form-control phone_number', 'id'=>'id_contact_phone_out', 'maxlength'=>'12', 'pattern'=>'^[0-9]{3}-[0-9]{3}-[0-9]{4}$', 'placeholder'=>'333-333-4444', 'title'=>'Example 333-333-4444' ]) !!}
        </div>

    </div>

@endif


<div class="form-group row">
    {!! Form::label('amount', 'Amount ($)', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::text('amount', null, ['id' => 'id_text_float_amount', 'required' => 'required', 'class' => 'form-control',
        'placeholder'=> $placeholder]) !!}

        <span id="ide_floating_amount" class="form-error" style="display: none">Amount should not be less than {{$minGrantAmount}}</span>
    </div>
</div>

@if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY') and !\App\Models\ClientInfo::isCCT())
    <div class="form-group row">
        {!! Form::label('frequency', \App\Models\GrantForm::frequencyLabel(), ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9">
            {!! Form::select('frequency', $frequencies, null, ['class' => 'form-control']) !!}

            @if(\App\Models\ClientInfo::isHGA())
                <span class="from-footer">Please note recurring grant distributions will occur on the second business day unless otherwise directed.</span>
            @endif
        </div>
    </div>
@endif

@include(\App\Models\ClientInfo::clientViewFor("grants._form_extras", "donor."))

<hr>

<div class="form-group row">
    <div class="offset-sm-3 col-sm-5 col-md-4">
        {!! Form::submit($model->cart_id ? 'Save' : 'Add to Cart', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent w100']) !!}
    </div>
</div>

<div class="text-right">
    <a href="{{ url()->previous() }}" class="cancel" onclick="">Cancel</a>
</div>

{{-- ORG INFO VIEW --}}
<div id="id_org_info">

    <div class="form-org-info">
        @if(\App\Models\ClientInfo::isCCT())
            <div class="row">
                <div id="id_add_org_info_text" class="offset-md-41 col-md-12">
                    <p style="border-bottom: none; margin-bottom: 0;">This organization was not found in our database. You may want to search for the organization again with different keywords.</p>
                    <p>To continue with this organization, please fill in the following information:</p>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('ein', 'EIN', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
                <div class="col-md-4">
                    {!! Form::text('ein', null, ['class' => 'form-control org_ein', 'id'=>'id_org_ein', 'maxlength'=>'10', 'pattern'=>'^[0-9]{2}-[0-9]{7}$', 'placeholder'=>'22-7777777', 'title'=>'Example 22-7777777' ]) !!}
                </div>
            </div>

        @else
            <div class="row">
                <div id="id_add_org_info_text" class="offset-md-41 col-md-12">
                    <p style="border-bottom: none; margin-bottom: 0;">The organization you have selected was not found in our database. You may want to search for the organization again with different keywords.</p>
                    <p>To continue with this organization, please fill in the contact name, if available, and one or more of the following: email, phone, EIN, or address.</p>
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('contact_name', 'Contact Person', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
                <div class="col-md-9">
                    {!! Form::text('contact_name', null, ['class' => 'form-control', 'id'=>'id_contact_name', 'maxlength'=>'32' ]) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('phone', 'Phone', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
                <div class="col-md-4">
                    {!! Form::text('phone', null, ['class' => 'form-control phone_number', 'id'=>'id_contact_phone', 'maxlength'=>'12', 'pattern'=>'^[0-9]{3}-[0-9]{3}-[0-9]{4}$', 'placeholder'=>'333-333-4444', 'title'=>'Example 333-333-4444' ]) !!}
                </div>

                {!! Form::label('ein', 'EIN', ['class' => 'col-md-1 col-form-label text-right pr-0']) !!}
                <div class="col-md-4">
                    {!! Form::text('ein', null, ['class' => 'form-control org_ein', 'id'=>'id_org_ein', 'maxlength'=>'10', 'pattern'=>'^[0-9]{2}-[0-9]{7}$', 'placeholder'=>'22-7777777', 'title'=>'Example 22-7777777' ]) !!}
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
                <div class="col-md-9">
                    {!! Form::email('email', null, ['class' => 'form-control', 'id'=>'id_contact_email', 'maxlength'=>'60', 'placeholder'=>'contact@example.com' ]) !!}
                </div>
            </div>
        @endif

        <div class="form-group row">
            {!! Form::label('address_one', 'Address Line 1', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-9">
                {!! Form::text('address_one', null, ['class' => 'form-control', 'id'=>'id_address_one', 'maxlength'=>'60' ]) !!}
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('address_two', 'Address Line 2', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-9">
                {!! Form::text('address_two', null, ['class' => 'form-control', 'placeholder' => 'optional', 'id'=>'id_address_two', 'maxlength'=>'60' ]) !!}
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('city', 'City', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                {!! Form::text('city', null, ['class' => 'form-control', 'id'=>'id_city', 'maxlength'=>'32' ]) !!}
            </div>

            {!! Form::label('state', 'State', ['class' => 'col-md-1 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                {!! Form::select('state', $states, $defaultState, ['id' => 'id_state', 'class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('country', 'Country', ['class' => 'col-md-3 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                {!! Form::select('country', $countries, 'USA', ['id' => 'id_country', 'class' => 'form-control']) !!}
            </div>

            {!! Form::label('zip', 'ZIP', ['class' => 'col-md-1 col-form-label text-right pr-0']) !!}
            <div class="col-md-4">
                {!! Form::text('zip', null, ['class' => 'form-control address_zip', 'id'=>'id_org_zip', 'maxlength'=>'5', 'pattern'=>'^[0-9]{5}$', 'placeholder'=>'optional', 'title'=>'Five digit zip code' ]) !!}
            </div>
        </div>

    </div>
</div>

<script>
    var grantForm = new GrantFrom();
    grantForm.client_id = "{{$clientId}}";

    // client specific - closing grant
    var isClosingGrant = false;
    var amountPlaceholder = null;

    function setClosingGrant(value) {
        var amountView = $('#id_text_float_amount');
        if (amountPlaceholder == null) {
            amountPlaceholder = amountView.attr('placeholder');
        }
        if (value == true) {
            isClosingGrant = true;
            $('#ide_floating_amount').hide();
            amountView.val('');
            amountView.prop('required', false);
            amountView.prop('disabled', true);
            amountView.prop('placeholder', '');
        } else {
            isClosingGrant = false;
            amountView.prop('required', true);
            amountView.prop('disabled', false);
            amountView.prop('placeholder', amountPlaceholder);
        }
    }

    $(function(){
        grantForm.init();
        document.getElementById('id_text_float_amount').addEventListener('blur', checkAmount);
    });

    var minGrantAmount = {{ $minGrantAmount }};
    function checkAmount() {
        if (isClosingGrant == true) return true;

        var amount = $('#id_text_float_amount').val();
        console.log("id_text_float_amount: ", amount);

        if (amount == undefined || amount < minGrantAmount) {
            $('#ide_floating_amount').show();
            return false;
        } else {
            $('#ide_floating_amount').hide();
            return true;
        }
    }
    $('body').on('click', '#id_save_btn', function (event) {
        if (!checkAmount()) {
            $('#id_text_float_amount').focus();
            return false;
        }
        return grantForm.onSave(this, event);
    });
</script>

