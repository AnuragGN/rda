@extends('layouts.main', ['container' => 'container login-container'])

<style>
    .gn-content { background: #fafafa; }
</style>

@section('content')
    <div class="options-2fa">

        @include('errors.form-errors')

        <div class="icon"><i class="fas fa-lock"></i></div>
        <p>Check your {{$type}}, we've sent you a code.</p>

        <p class="fw600">Please enter the 4 digit code sent to
            {!! $type == 'phone' ? '<i class="fas fa-mobile-alt"></i>' : '' !!}
            {!! $type == 'email' ? '<i class="far fa-envelope"></i>' : '' !!}
            {!! $address !!}
        </p>

        <form class="form" role="form" autocomplete="off" id="2faForm" method="POST" action="{{route('2fa-post')}}">

            @csrf

            <input type="hidden" name="token" value="{{$token}}" />

            <div class="form-group row" style="text-align: center">
                <div class="code-input-container">
                    <input id="code" type="number" class="form-control mt-2" name="code" required="" placeholder="4 digit code">
                </div>
            </div>

            <div class="form-group row">
                <div style="width: 200px; margin: 0 auto">
                    <button type="submit" class="btn btn-accent w100">
                        Submit Code
                    </button>
                </div>
            </div>

        </form>

        <br>
        <small>Code will expire in 15 minutes.</small>
        <br>
    </div>

    <br>

    <div style="text-align: center; font-weight: 500">
        <p class="mb-1"><a href="{{route('2fa-resend', ['token' => $token])}}">Send me a new code</a></p>
        <p><a href="{{route('root')}}">Home</a></p>
    </div>

    <br>
    <br>

    <script>
        $('#code').click(function() {
            $('.form-errors').html('');
        });
    </script>
@endsection
