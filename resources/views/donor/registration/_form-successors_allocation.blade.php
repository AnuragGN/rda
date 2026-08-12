@php
$maxIndividuals = \App\Models\ClientConfig::value('DAF_MAX_SUCCESSORS_INDIVIDUALS');
$maxOrgs = \App\Models\ClientConfig::value('DAF_MAX_SUCCESSORS_ORGANIZATIONS');
$total = App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
@endphp

<div class="form-group" id="id_allocation" >
    @if(\App\Models\ClientInfo::isHGA())
        @if ($successorDesignation)
            <p>
                HighGround requires each donor-advised fund to have at least one of the following succession strategies
                in place, to be activated upon the death or incapacity of the last remaining Donor Advisor. The combined
                percentages designated to all succession strategies must equal 100%. Donor Advisors can make changes to
                their succession strategy selections at any time.
            </p>
        @endif
    @else
        <p>
            The Fund Advisors need to develop a succession plan to succeed them
            and assume privileges on the Donor-Advised Fund. You can add up to {{$maxIndividuals}}
            Individuals and up to {{$maxOrgs}} Charitable Organizations.
        </p>
    @endif

    @if ($successorDesignation == false)
        <div class="row">
            <div class="col-12 col-form-label" style="font-weight: 600;">
                {{--<span class="">Current Total {{$total}}% of Giving Account 0%</span>--}}
                <span class="label-note total_allocation" id="total_allocation">Current total {{$givingTotal}}%</span>
                <span id= "total_warning" class="total_warning ml-3"></span>
                <br>
                <span class="label-note">Total must equal 100%</span>
            </div>
        </div>
    @endif
</div>