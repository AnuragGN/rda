@php
$donor = json_decode($fullDAFInfo['donor']);
$additionalDonors = json_decode($fullDAFInfo['donors']);
$contributions = json_decode($fullDAFInfo['contributions']);
$investments =  \App\Models\DAFAccount::getCurrentAllocation($id);
$givingTotal = \App\Models\DAFAccount::getTotalIndividualOrgPercent($id);

$personFieldsDonor = \App\Helpers\Data::getDonorInfoCustomFields();
$personFieldsAdditionalDonor = \App\Helpers\Data::getAdditionalDonorInfoCustomFields();
$personFieldsSuccessorsIndividual = \App\Helpers\Data::getSuccessorsIndividualCustomFields();

$contributionTypes = \App\Helpers\Data::getContributionTypes();

$donorInfo = \App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_DONOR, $id);
$addDonorInfo = \App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_ADDITIONAL_DONOR, $id);

$successors = json_decode($fullDAFInfo['successors']);
$individuals = \App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_SUCCESSORS_INDIVIDUALS, $id);
$orgs = \App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_SUCCESSORS_ORGANIZATIONS, $id);

$endowment = \App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_SUCCESSORS, $id);

$dafType = \App\Helpers\Data::getDAFTypeLabel($fullDAFInfo->daf_type);
@endphp

<style>
    .daf-review .form-title {
        background: #ddeef3;
        padding: 0.5rem 1rem;
        margin: 2rem 0 1rem;
        color: #212529!important;
        text-transform: uppercase;
        font-size: 1rem;
        border: none;
    }
    .daf-review label {
        color: #7e7f72;
        text-align: right;
        text-transform: uppercase;
        font-weight: 600;
        /*padding-right: 12px;*/
    }
    .daf-review .form-group-title {
        text-transform: uppercase;
        color: #9a907d!important;
    }

</style>

<div class="col-md-12">
    @if (@$successors->endowment->isSelected == false)
        @if ($givingTotal < 100 )
            <div class="form-group row">
                <span class="col alert alert-danger"> Successor allocation is {{$givingTotal}}%. You will not be able to continue unless allocation is 100%</span>
            </div>
        @elseif ($givingTotal > 100)
            <div class="form-group row">
                <span class="col alert alert-danger"> Successor allocation is {{$givingTotal}}%. You will not be able to continue unless allocation is 100%</span>
            </div>
        @endif
    @endif
</div>
{{--{!! Form::model($donor, ['method' => 'POST', 'files' => false, 'route' => ['post-agency-daf-account-info'], 'id' => 'daf-account-info-form']) !!}--}}
<div class="col-md-12">
    <div class="form-group">
        <p class="form-title mt-2">Donor-Advised Fund Name</p>
    </div>

    <div class="form-group form-group-info row">
        <label for="fund_name" class="col-md-3 col-form-label text-right pr-0">Fund Name</label>
        <div class="col-md-6">
            {{$fullDAFInfo->fund_name}}
        </div>
    </div>

    <div class="form-group">
        <h4 class="form-title">Primary Donor Advisor Information</h4>
    </div>

    @include('agency.agency-advisor.daf-registration.review-person-info', ['person' => $donorInfo['donor'], 'personFields' => $personFieldsDonor])
    @include('agency.agency-advisor.daf-registration.review-address-info', ['address' => $donorInfo['donor']])

    @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFieldsDonor))
        @include('agency.agency-advisor.daf-registration.review-mailing-address-info', ['mAddress' => $donorInfo['donor']])
    @endif

    @if( isset($additionalDonors->donors) )

        @foreach ($addDonorInfo['donors'] as $donor)

            <div class="form-group">
                <h4 class="form-title">{{ \App\Models\DAF\DAFAdditionalDonor::title() }}</h4>
            </div>

            @include('agency.agency-advisor.daf-registration.review-person-info',['person' => $donor, 'personFields' => $personFieldsAdditionalDonor])

            @include('agency.agency-advisor.daf-registration.review-address-info', ['address' => $donor])

            @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFieldsAdditionalDonor))
                @include('agency.agency-advisor.daf-registration.review-mailing-address-info', ['mAddress' => $donor])
            @endif

        @endforeach
    @endif

    @if($dafType)
        <div class="form-group">
            <h4 class="form-title">{{\App\Models\DAFAccount::DAF_TYPE_LABEL}}</h4>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right pr-0">DAF Type</label>
            <div class="col-md-4">
                {{$dafType}}
            </div>
        </div>
    @endif

    @if( isset($successors->endowment->isSelected) == true)
        <div class="form-group">
            <h4 class="form-title">{{\App\Models\DAF\DAFSuccessors::titleEndowmentReview()}}</h4>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right pr-0">Endowment name</label>
            <div class="col-md-4">
                @if ( isset($successors->endowment->endowment_name))
                    {{$successors->endowment->endowment_name}}
                @endif
            </div>
        </div>

    @else

        @if( isset($successors->individuals) && count($successors->individuals))

            @foreach($individuals['individuals'] as $individual)

                @if( isset($individual->isNew) == false)
                    <div class="form-group">
                        <h4 class="form-title">{{\App\Models\DAF\DAFSuccessors::title()}} - Individuals</h4>
                    </div>

                    @include('agency.agency-advisor.daf-registration.review-person-info',['person' => $individual, 'personFields' => $personFieldsSuccessorsIndividual])
                    @include('agency.agency-advisor.daf-registration.review-address-info', ['address' => $individual])
                @endif

            @endforeach
        @endif

        @if( isset($successors->organizations) && count($successors->organizations))

            @foreach($orgs['organizations'] as $index => $organization)

                @if( isset($organization->isNew) == false)

                    <div class="form-group">
                        <h4 class="form-title">{{\App\Models\DAF\DAFSuccessors::title()}} - Charitable Organizations</h4>
                    </div>

                    <div class="form-group row mb-0">
                        <label class="col-md-3 col-form-label text-right pr-0">Organization Name</label>
                        <div class="col-md-4">
                            {{$organization['org_name']}}
                        </div>

                        <label class="col-md-2 col-form-label text-right pr-0">Contact Name</label>
                        <div class="col-md-3">
                            {{$organization['contact_name']}}
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        @if (! \App\Models\ClientInfo::isHGA())
                            <label class="col-md-3 col-form-label text-right pr-0">EIN</label>
                        @else
                            <label class="col-md-3 col-form-label text-right pr-0">Federal Tax Payer ID#</label>
                        @endif

                        <div class="col-md-4">
                            {{$organization['ein']}}
                        </div>

                        <label class="col-md-2 col-form-label text-right pr-0">Phone</label>
                        <div class="col-md-3">
                            {{ App\Helpers\GnUtils::formatPhoneNumber($organization['phone_number']) }}
                        </div>

                    </div>

                        <div class="form-group row mb-0">
                            <label class='col-md-3 col-form-label text-right pr-0'>% of Giving Account</label>
                            <div class="col-md-4">
                                {{$organization['giving']}}
                            </div>
                        </div>

                        @include('agency.agency-advisor.daf-registration.review-address-info', ['address' => $organization])

                @endif
            @endforeach
        @endif

    @endif

    @if( isset($contributions->credit_card) )

        <div class="form-group">
            <h4 class="form-title">Contributions - Credit Card</h4>
        </div>
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label text-right pr-0">Paid Amount</label>
            <div class="col-md-4">
                {{\App\Helpers\GnUtils::StrToMoney($contributions->credit_card->amount)}}
            </div>
            <label class="col-md-2 col-form-label text-right pr-0">Transaction Date</label>

            <div class="col-md-3">
                {{ \App\Helpers\GnUtils::customDate($contributions->credit_card->transaction_date) }}
            </div>

        </div>

    @endif

    @if (isset($contributions->cash->wire_pay) || isset($contributions->cash->check_pay))

        <div class="form-group">
            <h4 class="form-title">Contributions - Cash Equivalents</h4>
        </div>

        @if ( isset($contributions->cash->wire_pay) )
            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Wire Amount</label>
                <div class="col-md-4">
                    {{\App\Helpers\GnUtils::StrToMoney($contributions->cash->wire_amount)}}
                </div>
                <label class="col-md-2 col-form-label text-right pr-0">Wire Bank Name</label>
                <div class="col-md-3">
                    {{$contributions->cash->wire_bank}}
                </div>
            </div>
        @endif

        @if ( isset($contributions->cash->check_pay) )
            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Check Amount</label>
                <div class="col-md-4">
                    {{\App\Helpers\GnUtils::StrToMoney($contributions->cash->check_amount)}}
                </div>

            </div>
        @endif

    @endif

    @if (isset( $contributions->securities) && count($contributions->securities))

        @foreach($contributions->securities as $security)

            <div class="form-group">
                <h4 class="form-title">Contributions - Securities or Mutual Funds</h4>
            </div>

            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Fund Name</label>
                <div class="col-md-4">
                    {{$security->fund_name}}
                </div>
                <label class="col-md-2 col-form-label text-right pr-0">Number of Shares</label>
                <div class="col-md-3">
                    {{$security->shares}}
                </div>
            </div>

            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Name of Account</label>
                <div class="col-md-4">
                    {{$security->name}}
                </div>
                @if(isset($security->custodian_name))
                    <label class="col-md-2 col-form-label text-right pr-0">Custodian Name</label>
                    <div class="col-md-3">
                        {{$security->custodian_name}}
                    </div>
                @endif
            </div>

            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Custodian Account#</label>
                <div class="col-md-4">
                    {{$security->account_number}}
                </div>
                <label class="col-md-2 col-form-label text-right pr-0">Approx Amount ($)</label>
                <div class="col-md-3">
                    {{$security->amount}}
                </div>
            </div>

        @endforeach

    @endif

    @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_STOCKS, $contributionTypes) && isset( $contributions->stocks ) )

        @forelse ($contributions->stocks as $stock)

            <div class="form-group">
                <h4 class="form-title">Contributions - Stocks</h4>
            </div>

            <div class="form-group row mb-0">
                <label class="col-md-3 col-form-label text-right pr-0">Name of Stock</label>
                <div class="col-md-4">
                    {{$stock->stock_name}}
                </div>
                <label class="col-md-2 col-form-label text-right pr-0">Number of Shares</label>
                <div class="col-md-3">
                    {{$stock->shares}}
                </div>
            </div>

        @empty
            <p>No details available</p>
        @endforelse
    @endif

    @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_OTHERS, $contributionTypes) && isset( $contributions->others->is_active ) )
        <div class="form-group">
            <h4 class="form-title">Contributions - Others</h4>
        </div>

        @if($contributions->others->is_active )
            <p>Other contribution is selected</p>
        @endif

    @endif

    <div class="form-group">
        <h4 class="form-title">{{\App\Models\DAF\DAFInvestment::title()}}</h4>
    </div>

    <div class="form-group row mb-0 pdf-pool pdf-pool-title">
        <div class="offset-md-1 col-md-7 col-8">
            <label class="col-form-label text-right pr-0">{{ \App\Models\DAF\DAFInvestment::poolTitle() }}</label>
        </div>

        <div class="col-md-4 col-4">
            <label class="col-form-label text-right pr-0">Allocation %</label>
        </div>
    </div>
    @foreach($investments as $investment)
        @if($investment->allocation)
            <div class="form-group row mb-0 pdf-pool">
                <div class="offset-md-1 col-md-7 offset-0 col-8">
                    {{$investment->pool_name}}
                </div>
                <div class="col-md-1 col-4 text-center">
                    {{$investment->allocation}}
                </div>
            </div>
        @endif
    @endforeach

</div>