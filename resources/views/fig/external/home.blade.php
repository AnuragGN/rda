
@extends('donor.layouts.main', ['container' => 'home-page', 'external' => true, 'navbar' => \App\Models\ClientInfo::customNav()])

<?php
$user = auth()->user();
$name = $user ? $user->username : 'none';

$classicHome = "//qa-fig.giftingnetwork.com";
?>

@section('content')

    <div class="container-fluid">
        <div class="row row-hero">
            <div class="hero-box">
                {{--<img src="/ma/images/fig/hero.jpg" alt="">--}}
                <img src="/ma/images/fig/hero.jpg" alt="">
                <div class="hero-overlay"></div>
                <div class="caption">
                    <span class="cap-title-big">Strengthen relationships through My Inspired Giving™</span><br>
                    <br>
                    <a href="{{$classicHome}}/register.go" class="btn btn-home btn-theme">Register as a Grant Seeker</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid home-dark-container">
        <div class="container" style="">
        <p>
            Making it easier for donors and their financial advisors to support your organization through charitable giving is what The Community Foundation for Inspired Giving is all about. We developed My Inspired Giving™, a best-in-class philanthropic giving tool, to help organizations like yours build your brand, strengthen donor relationships and provide a consistent source of income to achieve your mission.
        </p>
        </div>
    </div>

    <div class="container text-cards-container text-center">
        <div class="row">
            <div class="col-md-4">
                <div class="text-card">
                    <h2 class="title">Build a more engaging  brand with My Inspired Giving</h2>
                    <p class="text">Each My Inspired Giving portal is custom branded for your organization, linking donors and their advisors to your brand rather than a generic giving platform.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-card">
                    <h2 class="title">Strengthen donor relationships with My Inspired Giving</h2>
                    <p class="text">State-of-the art technology and access to donor-advised funds, the fastest growing charitable giving instrument, make it easy for donors to support the causes important to them and your organization.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-card">
                    <h2 class="title">Generate a consistent source of income with My Inspired Giving</h2>
                    <p class="text">A unique Give Now, Give Later, Give Forever™ feature, only available through The Community Foundation for Inspired Giving, ensures support for your cause(s) in both the short- and long-term.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="text-align: center">
        <div class="row">
            <div class="col-sm-6">
                <a href="javascript:void(0);" class="btn btn-home btn-theme" onclick="alert('Under development!')">Start a Fund</a>
            </div>
            <div class="col-sm-6">
                <a href="{{$classicHome}}/register.go" class="btn btn-home btn-theme">Register as a Grant Seeker</a>
            </div>
        </div>
    </div>

@endsection

