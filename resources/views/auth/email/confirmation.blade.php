<?php
$user = \App\Models\User::getSessionUser();
?>

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Email Change Confirmation'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">
                        <p>Your new email ({{$email}}) has been saved successfully.</p>
                        @if(!$user)
                            <b><a href="{{route('login')}}">Login</a></b>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

