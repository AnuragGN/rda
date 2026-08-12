@extends('layouts.main', ['container' => 'container login-container'])

<style>
    .gn-content { background: #fafafa; }
</style>

@section('content')
    <div class="options-2fa">

        <div class="icon"><i class="fas fa-lock"></i></div>
        <p>Choose a verification process to verify your identity.</p>

        @if($phone)
            <div class="option-2fa">
                <a href="{{route('2fa-form', ['type' => 'phone', 'token' => $token, 'send' => 1])}}">
                    <div class="info">
                        <div><i class="fas fa-sms start"></i></div>
                        <div>
                            <span class="fw600">Text me a temporary code</span>
                            <br><span>We will send a code to {{$phone}}</span>
                        </div>
                    </div>
                    <div><i class="fas fa-chevron-right"></i></div>
                </a>
            </div>
        @endif

        @if($email)
            <div class="option-2fa" style="margin-top: -1px;">
                <a href="{{route('2fa-form', ['type' => 'email', 'token' => $token, 'send' => 1])}}">
                    <div class="info">
                        <div><i class="far fa-envelope start"></i></div>
                        <div>
                            <span class="fw600">Mail me a temporary code</span>
                            <br><span>We will mail a code to {{$email}}</span>
                        </div>
                    </div>
                    <div><i class="fas fa-chevron-right"></i></div>
                </a>
            </div>
        @endif

        @if($phone)
            <br><br><small>By selecting my mobile number, I consent to receiving a one-time text message containing a code to validate my account.</small>
        @endif
        <br>
    </div>

    <br>
@endsection
