<?php
/** @var \App\Transaction $model */
?>
@extends ('donor.registration.main' )

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Payment Response'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last">

        <div class="offset-md-2 col-md-8">

            <h4>{{ $model->getStatusMessage() }}</h4>

            <div class="m-transactions gn-shadow">
                <div class="two-column header">
                    <span>{{ \App\Helpers\GnUtils::customDate($model->transaction_date) }}</span>
                    <span>{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
                </div>
                <span>From: </span>{{$model->account_type}} {{$model->account_number}}
                <br><span>To: </span> {{ \App\Models\DAFAccount::getDAFFundNameById($model->target_id)}}
                <br><span class="">Transaction Id: </span>{{$model->transaction_id}}
                <br><span class="">Reference Id: </span>{{$model->ref_id}}
                <br><span class="">Status: </span>{{$model->displayStatus}}
            </div>
            {{--<hr>--}}

            {{--<a href="{{ route('contribute') }}" class="btn btn-accent btn-sm">{{ $custom->text->MAKE_ANOTHER_GIFT }}</a>--}}
            <a href="{{ route('daf-contributions-cash', $id) }}" class="btn btn-accent btn-sm">Go to Contributions</a>
        </div>

    </div>
    </div>

@endsection
