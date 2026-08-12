<?php
$fps = route('forgot-password-form');
?>
<form class="form" role="form" autocomplete="off" id="loinForm" method="POST" action="{{ route('post-login') }}">

    @csrf

    <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-4 col-form-label ta-r">Username</label>

        <div class="col-md-12">
            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus2 placeholder="Username">
        </div>

    </div>

    <div class="form-group row {{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-md-4 col-form-label ta-r">Password</label>

        <div class="col-md-12">
            <div class="input-group">
                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
                        <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
                        <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
                    </div>
                </div>
            </div>
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
            <a class="font-small" href="javascript:void(0);" data-href="{{route('forgot-password-form')}}" onclick="onForgotPassword(this)">
                Forgot Password?
            </a>
        </div>
    </div>

    @if($custom->feature->DAF_REGISTRATION)
        @if(\App\Models\Config::enableDafAppNewDonor())
            <div class="form-group row text-right">
                <div class="col-12">
                    <a class="font-small" href="{{route('daf-account')}}">
                        Open a Donor-Advised fund
                    </a>
                </div>
            </div>
        @endif
    @endif

    @if(\App\Models\ClientInfo::isCCT())
        <br>
        <hr>
        <div class="text-center">
            Download @include('cct.registration.daf-program-guide-link')
        </div>
    @endif

    <script>
        function onForgotPassword(item)
        {
            var link = $(item).data('href');
            var email = $('#email').val();
            if (email && email != null) {
                link += '?email=' + email;
            }
            window.location.href = link;
        }
    
        function showPassword(item) {
            var targetId = $(item).data('target-id');
            var elem = $('#' + targetId);
            elem.parent().find(".fa-eye").show();
            elem.attr('type', 'text');
            $(item).hide();
        }
        function hidePassword(item) {
            var targetId = $(item).data('target-id');
            var elem = $('#' + targetId);
            elem.parent().find(".fa-eye-slash").show();
            elem.attr('type', 'password');
            $(item).hide();
        }
    </script>
</form>
