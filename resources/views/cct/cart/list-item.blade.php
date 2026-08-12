<?php
$address = $model->getOrgAddress();
?>

<div class="cart-grant gn-shadow" id={{'grant-'. $model->cart_id}} data-item-id={{$model->cart_id}}>

    <div class="cart-grant-info w100">
        @if($model->is_closing_grant)
            <div class="two-column">
                <div class="amount text-accent hide" id={{'value-'. $model->cart_id}}>0</div>
                <div style="font-size: 18px; font-weight: 600;">Fund Closing Grant</div>
                {!! Form::checkbox('selected[]', $model->cart_id, true, ['id' => 'cb_' . $model->cart_id, 'class' => 'checkbox-1x js_grant_cb']) !!}
            </div>
        @else
            <div class="two-column">
                <div class="amount" id={{'value-'. $model->cart_id}}>{{ \App\Helpers\GnUtils::money($model->amount) }}</div>
                {!! Form::checkbox('selected[]', $model->cart_id, true, ['id' => 'cb_' . $model->cart_id, 'class' => 'checkbox-1x js_grant_cb']) !!}
            </div>
        @endif

        <div class="item">
            <span class="key">Fund</span> {{ $model->fund ? $model->fund->name : '-'}}
        </div>

        @if(!$model->is_closing_grant)
            @if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY'))
                <div class="item">
                    <span class="key">{{\App\Models\GrantForm::frequencyLabel()}}</span>
                    {{ $model->grantingFrequency }}
                </div>
            @endif

            @if($model->isRecurring())
                <div class="item">
                    <span class="key">Occurrences</span>
                    {{ $model->displayRecurringCount() }}
                </div>
            @endif
        @endif

        <div class="item">
            <span class="key">Expected Payout Date</span>
            {{ \App\Helpers\GnUtils::customDate($model->requested_disbursement_date) }}
        </div>

        <div class="item">
            <span class="key">Anonymous</span>
            {{ $model->anonymous == 'Y' ? 'Yes' : 'No' }}
        </div>

        @if($model->anonymous == 'N')
            @if($model->show_advisor_name)
                <div class="item">
                    <span class="key">From</span>
                    {{ $model->from_name }}
                </div>
            @endif

            @if($model->show_advisor_address)
                <div class="frequency" style="margin-left: 36px">{!! $model->getTwoLineFromAddress() !!}</div>
            @endif
        @endif

        <div class="org-name">{{ $model->getOrgName() }}</div>
        <div class="org-address">{!! $address->getTwoLineAddress() !!}</div>

        @if($model->org_ein)
            <div class="item"><span class="key">EIN </span>{!! $model->org_ein !!}</div>
        @endif
        @if($model->org_contact)
            <div class="item"><span class="key">Contact Person </span>{!! $model->org_contact !!}</div>
        @endif
        @if($model->org_phone)
            <div class="item"><span class="key">Phone </span>{!! $model->org_phone !!}</div>
        @endif
        @if($model->org_email)
            <div class="item"><span class="key">Email </span>{!! $model->org_email !!}</div>
        @endif

        @if(strlen($model->grant_purpose) > 0)
            <div class="item"><span class="key">Purpose </span>{{ $model->grant_purpose }}</div>
        @endif

        @if(strlen($model->notes) > 0)
            <div class="item"><span class="key">Instructions </span>{{ $model->notes }}</div>
        @endif

        @if(strlen($model->dedication_type) > 0)
            <div class="item"><span class="key">{{$model->dedication_type}} </span>{{ $model->grant_dedication }}</div>
        @endif

        @if(strlen($model->notification_info) > 0)
            <div class="item"><span class="key">Send acknowledgement to </span>{{ $model->notification_info }}</div>
        @endif
    </div>

    <div class="actions">
        <a href="{{route('grant-edit', $model->cart_id)}}">Edit</a>
        <a href="javascript:void(0);"
           data-parent-id="{{'grant-'. $model->cart_id}}"
           data-href="{{route('grant-remove', ['id' => $model->cart_id])}}" class="js_remove_grant_from_cart">Remove</a>
    </div>

</div>
