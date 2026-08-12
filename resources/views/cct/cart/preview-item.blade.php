<?php
$address = $model->getOrgAddress();
?>

<div class="row">
    <div class="col-12">

        @if($model->cart_id)
            <input type="hidden" name="selected[]" value="{{ $model->cart_id }}">
        @else
            {{-- for Marjory Kaplan Fund --}}
            @include('donor.cart.mkf_input', ['model' => $model])
        @endif

        <div class="grant-preview gn-shadow">
            <div class="row gp-row">
                <div class="col-4 label">From fund</div>
                <div class="col-8 value">{{ $model->fund->name }}</div>
            </div>
            <div class="row gp-row">
                <div class="col-4 label">To organization</div>
                <div class="col-8 value"> {{ $model->getOrgName() }}</div>
                <div class="offset-4 col-8 value address">{!! $address->getTwoLineAddress() !!}</div>
            </div>

            @if($model->org_ein)
                <div class="row gp-row">
                    <div class="col-4 label">EIN</div>
                    <div class="col-8 value">{{ $model->org_ein }}</div>
                </div>
            @endif

            @if($model->org_contact)
                <div class="row gp-row">
                    <div class="col-4 label">Contact Person</div>
                    <div class="col-8 value">{{ $model->org_contact }}</div>
                </div>
            @endif

            @if($model->org_phone)
                <div class="row gp-row">
                    <div class="col-4 label">Phone</div>
                    <div class="col-8 value">{{ $model->org_phone }}</div>
                </div>
            @endif

            @if($model->org_email)
                <div class="row gp-row">
                    <div class="col-4 label">Email</div>
                    <div class="col-8 value">{{ $model->org_email }}</div>
                </div>
            @endif

            @if($model->is_closing_grant)
                <div class="row gp-row">
                    <div class="col-4 label">Amount</div>
                    <div class="col-8 value">Fund Closing Grant</div>
                </div>
            @else
                <div class="row gp-row">
                    <div class="col-4 label">Amount</div>
                    <div class="col-8 value"> {{ \App\Helpers\GnUtils::money($model->amount) }}</div>
                </div>

                @if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY'))
                    <div class="row gp-row">
                        <div class="col-4 label">{{\App\Models\GrantForm::frequencyLabel()}}</div>
                        <div class="col-8 value"> {{ $model->grantingFrequency }}</div>
                    </div>
                @endif

                @if($model->isRecurring())
                    <div class="row gp-row">
                        <div class="col-4 label">Occurrences</div>
                        <div class="col-8 value"> {{ $model->displayRecurringCount() }}</div>
                    </div>
                @endif
            @endif

            <div class="row gp-row">
                <div class="col-4 label">Expected Payout Date</div>
                <div class="col-8 value"> {{ \App\Helpers\GnUtils::customDate($model->requested_disbursement_date) }}</div>
            </div>

            @if(strlen($model->grant_purpose) > 0)
                <div class="row gp-row">
                    <div class="col-4 label">Purpose</div>
                    <div class="col-8 value"> {{ $model->grant_purpose }}</div>
                </div>
            @endif

            @if(strlen($model->notes) > 0)
                <div class="row gp-row">
                    <div class="col-4 label">Instructions</div>
                    <div class="col-8 value"> {{ $model->notes }}</div>
                </div>
            @endif

            @if(strlen($model->dedication_type) > 0)
                <div class="row gp-row">
                    <div class="col-4 label">{{ $model->dedication_type }}</div>
                    <div class="col-8 value">{{ $model->grant_dedication }}</div>
                </div>
            @endif

            @if(strlen($model->notification_info) > 0)
                <div class="row gp-row">
                    <div class="col-4 label">Send acknowledgement to</div>
                    <div class="col-8 value">{{ $model->notification_info }}</div>
                </div>
            @endif


            <div class="row gp-row">
                <div class="col-4 label">Anonymous</div>
                <div class="col-8 value"> {{ $model->anonymous == 'Y' ? 'Yes' : 'No' }}</div>
            </div>

            @if($model->anonymous == 'N')
                @if($model->show_advisor_name)
                    <div class="row gp-row">
                        <div class="col-4 label">From</div>
                        <div class="col-8 value"> {{ $model->from_name }}</div>
                    </div>
                @endif

                @if($model->show_advisor_address)
                    <div class="row gp-row">
                        <div class="offset-4 col-8 value address">{!! $model->getTwoLineFromAddress() !!}</div>
                    </div>
                @endif
            @endif

        </div>
        <br>

    </div>
</div>
