<?php
// TODO: HGA demo
if (\App\Models\ClientInfo::isHGA()) {

    $model->grantee = str_replace("Judith A. Keyes", "Marquette University", $model->grantee);
    // $model->grantee = str_replace("Marquette University", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Mount Mary University", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Milwaukee Public Museum Inc", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Gesu Parish", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Catholic Charities of Milwaukee", "The Smith Family", $model->grantee);

    // $model->grantee = str_replace("Local Initiatives Support Corp Milwaukee", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Common Ground Inc", "The Smith Family", $model->grantee);
    // $model->grantee = str_replace("Life Navigators Inc", "The Smith Family", $model->grantee);

    $model->grantee = str_replace("Milwaukee Ballet Company", "Allen Company", $model->grantee);
    $model->grantee = str_replace("Greater Milwaukee Foundation Interfund", "Allen Foundation Interfund Co.", $model->grantee);
    $model->grantee = str_replace("Mr. and Mrs. Arthur Saltzstein", "Mr. and Mrs. Allen", $model->grantee);
    // $model->grantee = str_replace("Milwaukee", "Allen", $model->grantee);

}

if (!isset($sidepane))
    $sidepane = false;

$repeatLabel = \App\Models\ClientInfo::isHGA() ? "Grant" : "Repeat";

?>


<div class="row">
    <div class="col-12">
        <div class="row-history">
            <div class="fund-info">
                <p class="fund-date text-primary-dark">{{ \App\Helpers\GnUtils::customDate($model->grant_date) }}</p>
                @if(\App\Models\ClientInfo::isCCT() and $model->orgExists())
                    <p class="fund-to">
                        <a href="{{route('organization', $model->organization_id)}}">{{$model->grantee}}</a>
                    </p>
                @else
                    <p class="fund-to">{{$model->grantee}}</p>
                @endif
                @if(!$sidepane)
                    <p class="fund-for">{{$model->grant_description}}</p>
                    @if($model->grant_purpose)
                        <p class="fund-purpose">Purpose: {{$model->grant_purpose}}</p>
                    @endif
                @endif
            </div>
            <div class="fund-granted">
                <span class="fund-amount text-primary-dark">{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
                @if ($showRepeat)
                    <span>
                        <a href="{{ route('grant-create', ['repeat' => $model->fund_grant_history_id]) }}" class="btn btn-light-theme btn-sm">{{$repeatLabel}}</a>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>



{{--<div class="row-item">--}}
    {{--<div class="row-info">--}}
        {{--<h4>{{$model->donor}}</h4>--}}

        {{--<div class="row-info">--}}
            {{--{{ $model->gift_date }},--}}
            {{--{{$model->amount}}, {{$model->subjectName}} <i class="fas fa-share-alt"></i><br>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<hr>--}}
