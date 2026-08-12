@extends('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Change ' . \App\Models\ClientConfig::text('INVESTMENTS')])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-8">

                    @include(\App\Models\ClientInfo::clientViewFor('investments.info-edit', 'donor.'))

                    @include('donor.investments._form-edit')

                    <br>
                    <hr>
                </div>
            </div>

            @include(\App\Models\ClientInfo::clientViewFor('investments.info-footnote', 'donor.'))

        </div>
    </div>

@endsection
