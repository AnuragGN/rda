@extends('layouts.main', ['container' => 'container login-container'])

@section('content')

    <div class="row">
        <div class="col-12 text-center" style="padding-top: 4rem;">
            <h3>To access the Admin Portal  <a href="/admin" target="_blank">Click Here</a>.</h3>
            <h3>To start the Emulation Mode, click on the profile link of a Donor Advisor on the SF Portal.</h3>

            <div class="mt-3">
                <a href="javascript:void(0);" onclick="onLogout()">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <spna>Log Out</spna>
                </a>
            </div>
        </div>
    </div>

@endsection
