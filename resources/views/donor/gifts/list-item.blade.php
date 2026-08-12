<?php
// TODO: HGA demo
if (\App\Models\ClientInfo::isHGA()) {
    $model->donor = str_replace("Judith A. Keyes", "The Smith Family", $model->donor);
    $model->donor = str_replace("Milwaukee Ballet Company", "Allen Company", $model->donor);
    $model->donor = str_replace("Greater Milwaukee Foundation Interfund", "Greater Foundation Inter-fund", $model->donor);
    $model->donor = str_replace("Mr. and Mrs. Arthur Saltzstein", "Mr. and Mrs. Arthur", $model->donor);
    $model->donor = str_replace("Milwaukee", "Allen", $model->donor);
}
?>

<div class="row">
    <div class="col-12">
        <div class="row-history">
            <div class="fund-info">
                <p class="fund-date text-primary-dark">{{ \App\Helpers\GnUtils::customDate($model->gift_date) }}</p>
                <p class="fund-to">{{$model->donor}}</p>
                @if($model->comment)
                    <p class="fund-for">{{$model->comment}}</p>
                @endif


            </div>
            <span class="fund-amount text-primary-dark">{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
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
