<?php
/** @var \App\Transaction $model */
?>
@extends ('donor.layouts.main', ['container' => "container history-container"] )

@section ('content')

    <div class="row">

        <div class="offset-md-2 col-md-8">

            <div class="gn-breadcrumbs">
                @include('donor.common.breadcrumbs')
            </div>

            <div class="row row-page-title">
                <div class="col-12">
                    <h1 class="page-title hide"></h1>
                </div>
            </div>
            <br>
            <br>

            <h4>{{ $model->getStatusMessage() }}</h4>

            <div class="m-transactions gn-shadow">
                <div class="two-column header">
                    <span>{{ \App\Helpers\GnUtils::customDate($model->transaction_date) }}</span>
                    <span>{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
                </div>
                <span>From: </span>{{$model->account_type}} {{$model->account_number}}
                <br><span>To: </span>{{$model->paid_to}}
                <br><span class="">Transaction Id: </span>{{$model->transaction_id}}
                <br><span class="">Reference Id: </span>{{$model->ref_id}}
                <br><span class="">Status: </span>{{$model->displayStatus}}
            </div>
            <hr>

            <a href="{{ route('contribute') }}" class="btn btn-accent btn-sm">{{ $custom->text->MAKE_ANOTHER_GIFT }}</a>
            <a href="{{ route('donor-home') }}" class="btn btn-accent btn-sm">Home</a>
        </div>


    </div>

@endsection
