@extends('donor.layouts.main')

@section('content')

    <div class="row" style="margin-top: 50px">
        <div class="col-md-8 offset-md-2">

            <!-- form card login -->
            <div class="card card-outline-secondary" style="background: white">

                <div class="card-header">
                    <h4 class="mb-0">Reset Password</h4>
                </div>

                <!--card-block-->
                <div class="card-block">
                    <form class="form" role="form" autocomplete="off" id="resetPasswordForm" method="POST"
                          action="{{ route('password.request') }}">

                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        @include('errors.form-errors')

                        <div class="form-group row" style="margin-top: 1rem">
                            <label for="reset-email" class="col-md-4 col-form-label ta-r">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="reset-email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email', $email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reset-password" class="col-md-4 col-form-label ta-r">Password</label>

                            <div class="col-md-6">
                                <input id="reset-password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reset-password-confirm" class="col-md-4 col-form-label ta-r">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="reset-password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       name="password_confirmation" required>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-accent">
                                    Change Password
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            setPageBgLight();
        });
    </script>
@endsection
