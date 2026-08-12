<?php
$user = \App\Models\User::getSessionUser();
?>

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Confirm New Email'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">
                        <p>The entered URL is either invalid or expired.</p>
                        @if(!$user)
                            <b><a href="{{route('login')}}">Login</a></b>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

