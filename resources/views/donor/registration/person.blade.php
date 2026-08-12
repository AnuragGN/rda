@php
$model = is_array($model ?? null) ? (object) $model : ($model ?? (object) []);
if (!isset($donorAccountInfo)) {
    $donorAccountInfo = false;
}
$relationship = \App\Helpers\Data::getDonorRelationshipList();
$phoneTypes = \App\Models\PhoneType::selectDAFContactPhoneTypes();
$prefixes = \App\Models\Prefix::getSelectable();
$suffixes = \App\Models\Suffix::getSelectable();
$fundPrivileges = \App\Models\DAFAccount::getDonorFundPrivilegesList();
$citizenship = \App\Models\DAFAccount::getDonorCitizenshipList();
$tTipFundPrivileges = null;
if(\App\Models\ClientInfo::isHGA()) {
    $tTipFundPrivileges = 'All Donor Advisors have <b><strong>Donor Advisor Full</strong></b> privileges to view, make changes and recommend grants. Interested Parties can either have <b><strong>Interested Party View</strong></b> privileges to view grants, contributions and correspondence and to make changes to the investment selections only OR <b><strong>Interested Party Grant</strong></b> privileges, which include all the privileges of Interested Party View as well as the right to recommend grants.';
}
@endphp

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_PREFIX, $personFields))
    <div class="form-group row">
        <label for="prefix" class="col-md-3 col-form-label text-right pr-0">Prefix</label>
        <div class="col-md-3">
            <select name="prefix" id="prefix" class="form-control">
                <option value=""></option>
                @foreach ($prefixes as $val => $label)
                    <option value="{{ $val }}" {{ old('prefix', $model->prefix ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<div class="form-group row account-name">
    <label for="first_name" class="col-md-3 col-form-label text-right pr-0">Name</label>
    <div class="col-md-3 mb-1">
        <input type="text" name="first_name" id="first_name" class="form-control"
               placeholder="first name" onkeypress="return /[a-z]/i.test(event.key)" maxlength="32"
               value="{{ old('first_name', $model->first_name ?? '') }}" required>
    </div>
    @if(\App\Models\ClientInfo::isHGA())
        <div class="middle-name mb-1" style="margin-right: 0;">
            <input type="text" name="middle_name" class="form-control"
                   placeholder="middle initial" onkeypress="return /[a-z]/i.test(event.key)" maxlength="1"
                   value="{{ old('middle_name', $model->middle_name ?? '') }}">
        </div>
    @endif
    <div class="col-md-3 mb-1">
        <input type="text" name="last_name" class="form-control"
               placeholder="last name" onkeypress="return /[a-z]/i.test(event.key)" maxlength="32"
               value="{{ old('last_name', $model->last_name ?? '') }}" required>
    </div>
</div>

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_SUFFIX, $personFields))
    <div class="form-group row">
        <label for="id_suffix" class="col-md-3 col-form-label form-multi-line-label text-right pr-0">Suffix<br>(optional)</label>
        <div class="col-md-3">
            <select name="suffix" id="id_suffix" class="form-control">
                <option value=""></option>
                @foreach ($suffixes as $val => $label)
                    <option value="{{ $val }}" {{ old('suffix', $model->suffix ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_PREFNAME, $personFields))
    <div class="form-group row">
        <label for="id_prefname" class="col-md-3 col-form-label text-right pr-0">Preferred Name<br>(optional)</label>
        <div class="col-md-3">
            <input type="text" name="preferred_name" id="id_prefname" class="form-control"
                   onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)" maxlength="32"
                   value="{{ old('preferred_name', $model->preferred_name ?? '') }}">
        </div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_FUND_PRIVILEGES, $personFields))
    <div class="form-group row">
        <label for="id_fund_privileges" class="col-md-3 col-form-label form-multi-line-label text-right pr-0">Fund Privileges</label>
        <div class="col-md-6 col-11">
            <select name="fund_privileges_key" id="id_fund_privileges" class="form-control" required>
                <option value=""></option>
                @foreach ($fundPrivileges as $val => $label)
                    <option value="{{ $val }}" {{ old('fund_privileges_key', $model->fund_privileges_key ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @include('common.tooltip-title-info', ['tooltipInfo' => $tTipFundPrivileges])
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_DOB, $personFields))
    <div class="form-group row date-of-birth">
        <label for="id_dob" class="col-md-3 col-form-label text-right pr-0">Date of Birth</label>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" name="dob" id="id_dob" class="form-control dob"
                       placeholder="mm-dd-yyyyy"
                       value="{{ old('dob', $model->dob ?? '') }}" required>
                <div class="input-group-append" id="id_calendar_icon" style="cursor: pointer; color: #666;">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_SSN, $personFields))
    <div class="form-group row ssn">
        <label for="id_ssn" class="col-md-3 col-form-label text-right pr-0">SSN#</label>
        <div class="col-md-3">
            <input type="text" name="ssn" id="id_ssn" class="form-control ssn"
                   onkeypress="return /[0-9]/i.test(event.key)"
                   value="{{ old('ssn', $model->ssn ?? '') }}" required>
        </div>
    </div>
@endif

<div class="form-group row">
    <label for="phone_number" class="col-md-3 col-form-label text-right pr-0 mb-1">Primary Phone</label>
    <div class="col-md-3 mb-1">
        <input type="text" name="phone_number" id="phone_number" class="form-control phone_number"
               value="{{ old('phone_number', $model->phone_number ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-1">
        <select name="phone_type" class="form-control" required>
            <option value=""></option>
            @foreach ($phoneTypes as $val => $label)
                <option value="{{ $val }}" {{ old('phone_type', $model->phone_type ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

@if ($donorAccountInfo)
    <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
        <div class="col-md-6">
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ $user->email }}" readonly>
        </div>
    </div>
@else
    <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
        <div class="col-md-6">
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $model->email ?? '') }}" required>
        </div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_CITIZENSHIP, $personFields))
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-right pr-0">Citizenship</label>
        <div class="col-md-9">
            @foreach ($citizenship as $key => $label)
                <div class="form-check form-check-inline">
                    <input type="radio" name="citizenship_key" id="citizenship_{{ $key }}"
                           class="form-check-input mr-0" value="{{ $key }}"
                           {{ old('citizenship_key', $model->citizenship_key ?? 'us_citizen') == $key ? 'checked' : '' }}>
                    <label class="form-check-label pl-1" for="citizenship_{{ $key }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_ADVISOR_RELATIONSHIP, $personFields))
    <div class="form-group row">
        <label for="id_relation" class="col-md-3 col-form-label form-multi-line-label text-right pr-0">
            Relationship to Primary Donor Advisor
        </label>
        <div class="col-md-6">
            <select name="relationship_key" id="id_relation" class="form-control" required>
                <option value=""></option>
                @foreach ($relationship as $val => $label)
                    <option value="{{ $val }}" {{ old('relationship_key', $model->relationship_key ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<script>
    $(function() {
        var dt = new Date();
        var theMaxYear = dt.getFullYear() - 18;
        var theMinYear = 1900;
        var format = 'MM-DD-YYYY';
        $("[id=id_dob]").daterangepicker({
            locale: { format: format },
            autoUpdateInput: false,
            showDropdowns: true,
            singleDatePicker: true,
            maxYear: theMaxYear,
            minYear: theMinYear
        }).on('apply.daterangepicker', function(ev, picker) {
            var year = picker.startDate.format('YYYY');
            if (year > theMaxYear) {
                alert("Please select year from the dropdown");
                $(this).val('');
                return;
            }
            $(this).val(picker.startDate.format(format));
        });
    });

    $("[id=id_calendar_icon]").click(function(event){
        event.preventDefault();
        $(this).parent().children('#id_dob').click();
    });
</script>
