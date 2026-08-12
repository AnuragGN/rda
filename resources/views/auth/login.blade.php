@extends('layouts.main', ['container' => 'container login-container'])

@section('content')

    <div class="row">
        <div class="col-lg-7 col-md-12 client-info">
            @include(\App\Models\ClientInfo::clientViewFor('auth.info-pane'))
        </div>

        <div class="login-form-2 col-lg-3 offset-lg-1 col-md-6 offset-md-3 login-card-box">
            <!-- form card login -->

            @include(\App\Models\ClientInfo::clientViewFor('auth.info-pane-subtitle'))

            @include('errors.form-errors')

            @include('auth._form_login')

            @include(\App\Models\ClientInfo::clientViewFor('auth.login-extras'))

        </div>
    </div>

@endsection
