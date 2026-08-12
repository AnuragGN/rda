@extends('layouts.main', ['container' => 'container login-container'])

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12 client-info">
            <h1 class="page-title">
                HighGround Advisors
            </h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin aliquam mi nec eros ultrices, eu viverra magna scelerisque. Cras gravida turpis sapien, ut viverra nulla pretium non. </p>
            <p>Quisque venenatis condimentum eros. Curabitur nec euismod lorem, ut laoreet nibh. Donec fringilla eget eros tempus vulputate. Ut et gravida leo. Sed fermentum dui non laoreet dignissim. </p>

        </div>

        <div class="col-lg-5 offset-lg-1 col-md-8 offset-md-2 login-card-box">
            <!-- form card login -->
            <div class="card card-login">

                <div class="card-header">
                    <span>Login</span>
                </div>

                <!--card-block-->
                <div class="card-body">
                    <form class="form" role="form" autocomplete="off" id="loinForm" method="POST" action="{{ route('post-login') }}">

                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-form-label ta-r">Username</label>

                            <div class="col-md-7">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="error-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                        </div>

                        <div class="form-group row {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label ta-r">Password</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="error-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row hide">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-accent mw120">
                                    Login
                                </button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <a class="font-small" href="{{route('forgot-password-form')}}">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
                <!--/card-block-->
            </div>
            <!-- /form card login -->

        </div>
    </div>

@endsection
