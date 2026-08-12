@php
$attempts = 0;
$user = null;
$user = \App\Models\User::getById($auth_user_id);
$max_account_activation_count = App\Http\Controllers\Auth\LoginController::MAX_ACCOUNT_ACTIVATION_LINKS;
$pwd_link = App\Models\PasswordLink::getAccountActivationLinkAttempts($auth_user_id);
if ($pwd_link) $attempts = $pwd_link->pwd_count;
@endphp

@extends ('donor.registration.main-account')

@section ('content')

    <div class="container mt-5">
        <div class="form-body">

            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-9">

                        @if(\App\Models\ClientInfo::isHGA())
                            <p>This email address already exists in the system. Please check your email and click the
                                confirmation link to access the donor-advised fund application.</p>

                            @if ($attempts < $max_account_activation_count)
                                <div class="form-group row">
                                    <form method="POST" action="{{ route('resend-account-activation-link') }}">
                                        @csrf
                                        <input type="hidden" name="auth_user_id" value="{{ $auth_user_id }}">
                                        <button type="submit" name="Resend Activation Link" class="btn btn-hga-md btn-theme ml-2">Resend Activation Link</button>
                                    </form>
                                </div>
                            @endif

                        @else

                            <h2 class="mb-3">Your Account is not activated.</h2>
                            <p>You can activate your account by clicking the link sent on your email id {{$user->username}} </p>

                            @if ($attempts < $max_account_activation_count)
                                <p>If you have not received the account activation link, click below to resend it.</p>
                                <div class="form-group row">
                                    <form method="POST" action="{{ route('resend-account-activation-link') }}">
                                        @csrf
                                        <input type="hidden" name="auth_user_id" value="{{ $auth_user_id }}">
                                        <button type="submit" name="Resend Activation Link" class="btn btn-hga-md btn-theme ml-2">Resend Activation Link</button>
                                    </form>
                                </div>
                            @else
                                <p class="pl-2 mt-1">Please check your emails for account activation link.</p>
                            @endif

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection