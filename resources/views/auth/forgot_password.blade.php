@extends('layouts.main', ['container' => 'container forgot-password-container'])


@section('content')

    <style>
        .gn-content { background: #fafafa; }
    </style>

    <!-- form card login -->
    <div class="forgot-password">

        <div class="icon"><i class="fas fa-lock"></i></div>
        <h4>Trouble Logging In?</h4>
        <p>Enter your email and we'll send you a link to get back into your account.</p>

        @include('errors.form-errors')

        <form method="POST" action="{{ route('forgot-password') }}" id="forgot-password-form">
            @csrf

            <div class="form-group row">
            <div class="col-sm-12">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Your email">
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <button type="submit" name="save" class="btn btn-accent w100">Send Link</button>
            </div>
        </div>

        </form>

        <br>
        <a href="{{\App\Helpers\GnUtils::userHomeUrl()}}" class="font-small">Back To Home</a>
    </div>
    <!-- /form card login -->

    @if(\App\Models\ClientInfo::isHGA())
        <div style="max-width: 600px; text-align: center; margin: 0 auto; margin-top: 2rem">
            <p>If you cannot remember the email address associated with your fund or have any other questions, please email us at
                <a href="mailto:dafs@highgroundadvisors.org">dafs@highgroundadvisors.org</a> for support.</p>
        </div>
    @endif

@endsection
