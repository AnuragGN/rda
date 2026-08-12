<?php
$address = $model->getOrgAddress();
if (\App\Models\ClientInfo::isJCF()) {
    $type = $model->anonymous == "N" ? '' : '(Anonymous)';
} else {
    $type = $model->anonymous == "N" ? '(Non-anonymous)' : '(Anonymous)';
}
?>

<div class="cart-grant gn-shadow" id={{'grant-'. $model->cart_id}} data-item-id={{$model->cart_id}}>

    <div class="cart-grant-info w100">
        <div class="two-column hide">
            <div class="amount text-accent" id={{'value-'. $model->cart_id}}>{{ \App\Helpers\GnUtils::money($model->amount) }}</div>
            {!! Form::checkbox('selected[]', $model->cart_id, true, ['id' => 'cb_' . $model->cart_id, 'class' => 'checkbox-1x js_grant_cb']) !!}
        </div>
        <div class="fund-name">From {{ $model->fund ? $model->fund->name : '-'}}
            @if(!\App\Models\ClientInfo::isHGA())
                {{ $type }}
            @endif
        </div>

        @if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY'))
            <div class="frequency">{{\App\Models\GrantForm::frequencyLabel()}}: {{ $model->grantingFrequency }}</div>
        @endif

        @if(\App\Models\ClientInfo::isHGA())
            <div class="frequency">Anonymous: {{ $model->anonymous == 'Y' ? 'Yes' : 'No' }}</div>
        @endif

        <div class="org-name">{{ $model->getOrgName() }}</div>
        <div class="org-address">{!! $address->getTwoLineAddress() !!}</div>

        @if($model->org_ein)
            <div class="org-info"><span class="subtitle">EIN </span>{!! $model->org_ein !!}</div>
        @endif
        @if($model->org_contact)
            <div class="org-info"><span class="subtitle">Contact Person </span>{!! $model->org_contact !!}</div>
        @endif
        @if($model->org_phone)
            <div class="org-info"><span class="subtitle">Phone </span>{!! $model->org_phone !!}</div>
        @endif
        @if($model->org_email)
            <div class="org-info"><span class="subtitle">Email </span>{!! $model->org_email !!}</div>
        @endif

        @if(strlen($model->grant_purpose) > 0)
            <div class="purpose"><span class="subtitle">Purpose </span>{{ $model->grant_purpose }}</div>
        @endif

        @if(strlen($model->dedication_type) > 0)
            <div class="purpose"><span class="subtitle">{{$model->dedication_type}} </span>{{ $model->grant_dedication }}</div>
        @endif

        @if(strlen($model->notes) > 0)
            <div class="note"><span class="subtitle">Note </span>{{ $model->notes }}</div>
        @endif

        @if(strlen($model->getGrantFromName()) > 0)
            <div class="note"><span class="subtitle">From </span>{{ $model->getGrantFromName() }}</div>
        @endif
    </div>
    <div class="actions">
        <a style="color:#fff;" class="btn btn-accent btn-sm" onclick="get_notification_popup({{ $model->cart_id }});">Send Notification </a>
        <a href="{{route('agency-cart')}}">Back</a>
    </div>
</div>
@include('agency.agency-advisor.cart.advisor_js')