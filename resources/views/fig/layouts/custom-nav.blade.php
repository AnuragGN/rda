<?php
$guest = Auth::guest();
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();

$badgeCount = \App\Models\GrantItem::countCartItems();

$classicHome = "//qa-fig.giftingnetwork.com";
?>

<style>
    @media (min-width: 768px){
        img.gn-logo {
            height: 90px;
        }
    }
</style>

<header>

    <nav class="navbar navbar-light navbar-dark2 navbar-expand-md gn-navbar jcf-navbar">

        <div class="container">
            <a class="navbar-brand" href="{{ route('root') }}">
                <i class="fab fa-bandcamp hide"></i>
                <img src="{{\App\Models\ClientInfo::logo()}}" class="d-inline-block align-top gn-logo" alt="">
                <span class="hide"> {{ config('app.name') }}</span> </a>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="fas fa-ellipsis-v"></i>
                {{--<span class="navbar-toggler-icon"></span>--}}
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse" style="justify-content: flex-end;">

                @if ($guest)
                    <div class="d-none d-md-block">
                        <ul class="navbar-nav ml-auto">

                            <li class="nav-item">
                                <a class="nav-link nav-link-cart" href="/about" style="position: relative">
                                    About Us
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link nav-link-cart" href="/for-donors" style="position: relative">
                                    For Donors
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link nav-link-cart" href="/for-organizations" style="position: relative">
                                    For Organizations
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link nav-link-cart" href="/for-advisors" style="position: relative">
                                    For Advisers
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link nav-link-cart btn btn-home btn-theme" href="javascript:void(0)"
                                   onclick="alert('Under development!')" style="position: relative">
                                    Start a Fund
                                </a>
                            </li>

                        </ul>
                    </div>

                @endif
                @if (!$guest)

                    {{-- Mobile --}}
                    <div class="d-block d-md-none">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('grant-create') }}">{{ $custom->text->MAKE_A_GRANT }}r̥</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('my-cart') }}">My Cart {{ $badgeCount ? '(' . $badgeCount . ')': '' }}</a>
                            </li>
                            <li class="nav-item PHASE2">
                                <a class="nav-link" href="{{ route('profile') }}">My Profile</a>
                            </li>
                            <li class="nav-item">
                                @include('utils.logout2', ['class' => 'nav-link'])
                            </li>
                            <li class="nav-item text-center">
                                <span> {{ $contact->name }} </span>
                            </li>
                        </ul>
                    </div>

                    {{-- Desktop --}}
                    <div class="d-none d-md-block">
                        <ul class="navbar-nav ml-auto">

                            <li class="nav-item">
                                <a class="nav-link nav-link-cart" href="{{ route('my-cart') }}" style="position: relative">
                                    @if ($badgeCount)
                                        <div id="jsid-sm-badge" class="icon-badge">{{$badgeCount}}</div>
                                    @endif
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle"
                                   href="#"
                                   id="navbarDropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $contact->name }} <i class="fas fa-user"></i>
                                    <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('grant-create') }}">{{ $custom->text->MAKE_A_GRANT }}</a>
                                    <a class="dropdown-item" href="{{ route('my-cart') }}">My Cart {{ $badgeCount ? '(' . $badgeCount . ')': '' }}</a>
                                    <a class="dropdown-item PHASE2" href="{{ route('profile') }}">My Profile</a>
                                    <div class="dropdown-divider"></div>
                                    @include('utils.logout', ['class' => 'dropdown-item'])
                                </div>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>

    </nav>


    <div id="nav-extras">
        <ul>
            <li>
                <a class="nav-extras-view" href="{{route('root')}}" target="_blank">DAF Login</a>
            </li>
            <li class="hide">
                <a class="nav-extras-view" href="{{$classicHome}}/login.go" target="_blank">Donor Login</a>
            </li>
            <li>
                <a class="nav-extras-view" href="{{$classicHome}}/login.go" target="_blank">Grant Seeker Login</a>
            </li>
        </ul>
    </div>

    <div id="nav-powered">
        <a class="nav-powered-view" href="//giftingnetwork.com/" target="_blank"><img src="/ma/images/gn-logo-sm.png">Powered by GiftingNetwork</a>
    </div>

</header>

<script>
    $(window).resize(function(){ poweredBy(); });
    $(function(){ poweredBy(); });

    $(window).resize(function(){ navExtras(); });
    $(function(){ navExtras(); });

</script>