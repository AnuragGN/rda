@extends('layouts.main', ['container' => 'container reset-password-container'])

<style>
    .gn-content {
        background: #fafafa;
    }
</style>

@section('content')

        <!-- form card login -->
<div class="forgot-password">

    <div class="icon"><i class="fas fa-unlock"></i></div>
    <h4>Reset Password</h4>

    @if(\App\Models\ClientInfo::isJCF())
        <p class="font-small">
            Password must be <span class="fw600">8 or more characters</span> in length,
            including one <span class="fw600">uppercase</span>, one
            <span class="fw600">lowercase</span>,
            and one <span class="fw600">numeric digit</span>.
        </p>
    @else
        <p class="font-small">Password must be at least <span class="fw600">8 characters long</span> and must have
            one <span class="fw600">uppercase character</span>,
            one <span class="fw600">lowercase character</span>,
            one <span class="fw600">special character</span> and
            one <span class="fw600">number</span>.
        </p>
    @endif

    @include('errors.form-errors')

    <form method="POST" action="{{ route('reset-password') }}" id="reset-password-form">
        @csrf

        <input type="hidden" name="token" value="{{ old('token', $model->token ?? '') }}">

    <div class="form-group row">
        <div class="col-sm-12">
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $model->email ?? '') }}" readonly disabled placeholder="Username">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required placeholder="Confirm Password">
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <button type="submit" name="save" class="btn btn-accent w100">Reset Password</button>
        </div>
    </div>

    </form>

    <br>
    <a href="/" class="font-small">Back To Home</a>
</div>
<!-- /form card login -->

<div class="container">
    <div class="row text-center">
        <div class="offset-lg-2 col-lg-8">
            @if(\App\Models\ClientInfo::isJCF())
                <p class="font-small">
                    Password must be <span class="fw600">8 or more characters</span> in length,
                    including one <span class="fw600">uppercase</span>, one
                    <span class="fw600">lowercase</span>,
                    and one <span class="fw600">numeric digit</span>.
                </p>
            @else
                <p class="font-small">Password must be at least <span class="fw600">8 characters long</span> and must have
                    one <span class="fw600">uppercase character</span>,
                    one <span class="fw600">lowercase character</span>,
                    one <span class="fw600">special character</span> and
                    one <span class="fw600">number</span>.
                </p>
            @endif
        </div>
    </div>
</div>

@endsection
