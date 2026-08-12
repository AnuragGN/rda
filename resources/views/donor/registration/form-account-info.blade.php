@php
$donors = App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_ADDITIONAL_DONOR, $id);
$additionalDonors = isset($donors['donors']) ? $donors['donors'] : '';
$maxAdditionalDonors = App\Models\ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR');
$personFields = App\Helpers\Data::getDonorInfoCustomFields();

$tTipFundName = null;
if(\App\Models\ClientInfo::isHGA()){
    $tTipFundName = 'Please start your fund name with "The" and end with "Fund." Names with the words "Trust," "Foundation" or "Endowment" are not accepted.';
}
@endphp

@extends('donor.registration.main')

@section('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFDonor::title(), 'split84' => true])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">
                <div class="col-md-8">

                    <form method="POST"
                          action="{{ route('post-daf-account-info', $id) }}"
                          id="daf-account-info-form">

                        @csrf

                        <div class="form-group">
                            @include('errors.form-errors')
                        </div>

                        <div class="form-group">
                            <p class="form-title">Donor-Advised Fund Name</p>
                        </div>

                        <div class="form-group row">
                            <label for="fund_name" class="col-md-3 col-form-label text-right pr-0">
                                Fund Name
                            </label>

                            <div class="col-md-6 col-11">
                                <input type="text"
                                       name="fund_name"
                                       id="fund_name"
                                       class="form-control"
                                       value="{{ old('fund_name', $model->fund_name ?? '') }}"
                                       onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                                       placeholder='e.g. "The Johnson Family Fund"'
                                       required>
                            </div>

                            @include('common.tooltip-title-info', ['tooltipInfo' => $tTipFundName])
                        </div>

                        <div class="form-group mt-4">
                            <p class="form-title">Primary Donor Advisor Information</p>
                        </div>

                        {{-- KEEP AS IS --}}
                        @include('donor.registration.person', ['donorAccountInfo' => true])

                        @include('donor.registration.address')

                        @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields))
                            @include('donor.registration.mailing-address')
                        @endif

                        <div class="form-btn-bar">
                            <div class="col-md-12 form-footer">
                                <div class="row">

                                    <p class="offset-md-3 col-md-3">
                                        <button type="submit"
                                                name="save"
                                                id="id_save_btn"
                                                class="btn btn-accent w100">
                                            SAVE
                                        </button>
                                    </p>

                                    @if(count($additionalDonors) < $maxAdditionalDonors)
                                        <p class="col-md-3">
                                            <button type="submit"
                                                    name="save_next"
                                                    class="btn btn-accent w100">
                                                SAVE & NEXT
                                            </button>
                                        </p>
                                    @endif

                                </div>
                            </div>
                        </div>

                        @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-account-info", "donor."))

                    </form>

                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("registration.side-pane-account-info", "donor."))
                </div>
            </div>
        </div>
    </div>

@endsection