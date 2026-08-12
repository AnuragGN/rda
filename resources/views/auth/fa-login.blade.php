@extends('layouts.main', ['container' => 'container cxontainer-fluid login-container'])

@section('content')

    <style>
        .gn-navbar {
            margin-bottom: 0!important;
        }
        .login-container {
            margin-top: 0;
        }
        .theImageIn {
            background: url('/ma/images/nif/welcome.jpg');
            background-position: center!important;
            height: 100%;
            width: 100%;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            /*min-height: 400px;*/
            /*min-height: 536px;*/
        }
        .login-pills {
            margin: 1rem;
        }

        .nav-pills .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0;
        }
        .nav-pills .nav-link {
            /*color: #6c757d;*/
            font-weight: 600;
            padding: 12px 16px;
            color: #fff;
        }
        .tab-content>.tab-pane p {
            color: #fff;
            margin: 2rem 1rem;
        }

        .theImageIn {
            max-height: 300px;
        }

        @media (min-width: 768px) {
            .theImageIn {
                min-height: 400px;
            }
        }

        /* Large devices (desktops, 992px and up)*/
        @media (min-width: 992px) {
            .theImageIn {
                min-height: 536px;
            }
        }

        /* Extra large devices (large desktops,*/
        @media (min-width: 1200px) {
            .theImageIn {
                min-height: 600px;
            }
        }

    </style>

    <div class="row">

        <div class="col-xl-4 col-lg-3 pl-0 pr-0" style="background: transparent ; /*#033751*/">
            <div class="theImageIn" id="id_the_image"></div>
        </div>

        <div class="col-xl-4 col-lg-5 col-md-7" style="background: #033751; opacity: 0.9">
            <br>
            <ul class="nav nav-pills login-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Welcome</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">About NIF</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Contact Us</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <p>The Progressive Jewish Fund (PJF), a program sponsored by the New Israel Fund, is the only national progressive Jewish DAF program. NIF’s PJF allows you to consolidate your support for a breadth of charities in both Israel and the U.S. in one convenient, efficient account, while providing meaningful support to NIF’s mission. By giving through PJF, you are aligning your philanthropy with your values and amplifying the voices of the progressive Jewish community. Thank you for your partnership!</p>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <p>The New Israel Fund (NIF) protects and advances equality and democracy for everybody in Israel. We believe that Israel can live up to its founders’ vision of a state that ensures complete equality of social and political rights to all its inhabitants, without regard to religion, race, gender or national identity. Widely credited with building Israel's progressive civil society from scratch, we have provided over $300 million to more than 900 cutting-edge organizations since our inception. NIF is proud to sponsor the Progressive Jewish Fund. Click here to visit NIF’s website</p>
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <p>For more information, or to open a fund, please contact Jennifer Spitzer, Vice President of Finance, Operations & Administration, at 415-543-5055, or jennifer@nif.org.</p>

                    <p>For wire instructions or stock transfer instructions, please follow this link or contact Andrew Goldblatt at andrewg@nif.org</p>
                </div>
            </div>

        </div>

        <div class="login-form-2 col-lg-4 col-md-5 login-card-box">

            <div class="row">
                <div class="offset-1 col-10">

                    <!-- form card login -->
                    @include(\App\Models\ClientInfo::clientViewFor('auth.info-pane-subtitle'))

                    @include('errors.form-errors')

                    <form class="form" role="form" autocomplete="off" id="loinForm" method="POST" action="{{ route('post-login') }}">

                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-form-label ta-r">Username</label>

                            <div class="col-md-12">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus2 placeholder="Username">
                            </div>

                        </div>

                        <div class="form-group row {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label ta-r">Password</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-accent w100">
                                    Log In
                                </button>
                            </div>
                        </div>

                        <div class="form-group row text-right">
                            <div class="col-12">
                                <a class="font-small" href="{{route('forgot-password-form')}}">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            @include(\App\Models\ClientInfo::clientViewFor('auth.login-extras'))

        </div>


    </div>

    <script type="text/javascript">
        function setImageHeight(){
            const height = window.innerHeight;
            const height1 = $('.gn-navbar').height();
            const height2 = $('.gn-footer').height();

            console.log('height: ', height);
            console.log('height1: ', height1);
            console.log('height2: ', height2);

            var h = height - height1 - height2;
            $('#id_the_image').css('height', h);
        }

        $(window).resize(function(){ setImageHeight(); });
        $(function(){ setImageHeight(); });

    </script>
@endsection
