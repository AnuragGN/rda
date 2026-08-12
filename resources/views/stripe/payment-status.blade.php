@extends ('donor.layouts.main', ['container' => "container history-container"] )

@section ('content')

    <div class="row">

        <div class="offset-md-2 col-md-8">
            <br>

            <h4> {{$transaction->message}} </h4>

            <div class="m-transactions gn-shadow">
                <div class="two-column header">
                    <span>{{ \App\Helpers\GnUtils::customDate($transaction->transaction_date) }}</span>
                    <span>{{ \App\Helpers\GnUtils::money($transaction->amount) }}</span>
                </div>
                <span>From: </span>{{$transaction->account_type}} {{$transaction->account_number}}
                <br><span>To: </span>{{$transaction->paid_to}}
                <br><span class="">Transaction Id: </span>{{$transaction->transaction_id}}
                <br><span class="">Reference Id: </span>{{$transaction->ref_id}}
                <br><span class="">Status: </span>{{$transaction->displayStatus}}

            </div>
            <hr>

            <a href="{{ route('contribute') }}" class="btn btn-accent btn-sm">{{ $custom->text->MAKE_ANOTHER_GIFT }}</a>
            <a href="{{ route('donor-home') }}" class="btn btn-accent btn-sm">Home</a>
        </div>

    </div>

@endsection
