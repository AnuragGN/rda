<?php
$address = $model->getOrgAddress();
?>

<div class="cart-grant gn-shadow">

    <div class="cart-grant-info w100">

        <div class="two-column">
            <div class="amount" id={{'value-'. $model->fund_recommendation_id}}>{{ \App\Helpers\GnUtils::money($model->amount) }}</div>
        </div>

        <div class="item"><span class="key">Fund</span> {{ \App\Models\Fund::getNameById($model->fund_id) }}</div>

        <div class="item">
            <span class="key">{{\App\Models\GrantForm::frequencyLabel()}}</span>
            {{ $model->grantingFrequency }}
        </div>

        <div class="org-name">{{ $model->org_name }}</div>
        <div class="org-address">{!! $model->getTwoLineFromAddress() !!}</div>

        @if($model->org_contact)
            <div class="item"><span class="key">Contact Person </span>{!! $model->org_contact !!}</div>
        @endif
        @if($model->org_phone)
            <div class="item"><span class="key">Phone </span>{!! $model->org_phone !!}</div>
        @endif
        @if($model->org_email)
            <div class="item"><span class="key">Email </span>{!! $model->org_email !!}</div>
        @endif

        <div class="item"><span class="key">Next Run Date</span> {{ \App\Helpers\GnUtils::customDate($model->next_run_date) }}</div>
        <div class="item"><span class="key">Last Grant Date</span> {{ \App\Helpers\GnUtils::customDate($model->last_grant_date) }}</div>
        <div class="item"><span class="key">Total #</span> {{$model->displayRecurringCount()}}</div>

        @if(!$model->isOngoing())
            <div class="item"><span class="key">Remaining</span> {{$model->remaining_grants}}</div>
        @endif

        <div class="item"><span class="key">Status</span> <span style="text-transform: capitalize">{{$model->recurring_status}}</span></div>
    </div>

    @if($model->isCancelable())
        <div class="actions">
            {{--<a href="{{route('cancel-recurring-grant', $model->fund_recommendation_id)}}">Cancel</a>--}}

            <a href="javascript:void(0);"
               data-href="{{route('cancel-recurring-grant', $model->fund_recommendation_id)}}"
               class="js_cancel_recurring_grant">Cancel</a>
        </div>
    @endif

</div>
