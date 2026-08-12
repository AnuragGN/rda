@extends('donor.layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">

            <!-- form card login -->
            <div class="card card-outline-secondary">

                <div class="card-header">
                    <p class="mb-0">Reset Password</p>
                </div>

                <!--card-block-->
                <div class="card-block">

                    <form class="form" role="form" autocomplete="off" id="resetPasswordForm" method="POST"
                          action="{{ route('password.email') }}">

                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4  col-form-label ta-r">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
