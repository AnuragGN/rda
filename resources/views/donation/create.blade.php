@extends('layouts.main')

@section('content')

    <div class="row">
        <div id="id_form_container" class="col-lg-8 col-r-15">
            @include('donation._form_donation')
        </div>
        <div class="col-lg-4 col-l-15">
            @include('pane-placeholder')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-r-15">
            <div id="id_btn_home" class="text-center mb-3" style="display: none">
                <a href="/" class="btn btn-theme" onclick="">Home</a>
            </div>
        </div>
    </div>

    @include('donor.transactions.modal-in-progress')

@endsection
