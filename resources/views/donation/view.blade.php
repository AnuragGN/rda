@extends('donor.layouts.main')

@section('content')

    <div class="row">
        <div class="col-lg-8">
            @include('donation.donation')
        </div>
        <div class="col-lg-4">
            @include('pane-placeholder')
        </div>
    </div>

@endsection
