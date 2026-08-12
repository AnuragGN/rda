<?php
$guest = Auth::guest();
if (!isset($errorPage)) {
    $errorPage = false;
    if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
    $badgeCount = \App\Models\GrantItem::countCartItems();
}
?>

<header>

    <nav class="navbar navbar-light navbar-dark2 navbar-expand-md gn-navbar jcf-navbar">

        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fab fa-bandcamp hide"></i>
                <img src="{{\App\Models\ClientInfo::logo()}}" class="d-inline-block align-top gn-logo" alt="">
                <span class="hide"> {{ config('app.name') }}</span> </a>

            @if(\App\Models\ClientInfo::isHGA())
                @if ($guest && !$errorPage)
                    <div class="nav-phone">CALL OUR OFFICE 800.747.5564</div>
                @endif
            @endif

            @if (false && !$guest && !$errorPage)

                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                        data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                        aria-label="Toggle navigation">
                    {{--<i class="fa fa-bars" aria-hidden="true"></i>--}}
                    {{--<i class="fas fa-ellipsis-v"></i>--}}
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse" style="justify-content: flex-end;">

                    {{-- Mobile --}}
                    <div class="d-block d-md-none">
                        <ul class="navbar-nav mr-auto nbnav-mobile">

                            @if($custom->feature->CONTRIBUTION)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('contribute') }}">
                                        <div>
                                            <div class="title">{{ $custom->text->MAKE_A_GIFT }}</div>
                                            <div class="subtitle">Add funds to your account</div>
                                        </div>
                                    </a>
                                </li>
                            @endif

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('grant-create') }}">
                                        <div>
                                            <div class="title">{{ $custom->text->MAKE_A_GRANT }}</div>
                                            <div class="subtitle">Send funds to non-profit</div>
                                        </div>
                                    </a>
                                </li>

                                @if($custom->feature->RECENT_CONTRIBUTIONS)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('transactions') }}">{{$custom->text->RECENT_CONTRIBUTIONS}}</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('my-cart') }}">{{ \App\Models\GrantForm::cartLabel() }} {{ $badgeCount ? '(' . $badgeCount . ')': '' }}</a>
                                </li>


                                @if($custom->feature->CHARITABLE_CATALOG)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('charitable-catalog') }}">{{ $custom->text->CHARITABLE_CATALOG }}</a>
                                    </li>
                                @endif


                                @if($custom->feature->APP_PROFILE)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('profile') }}">My Profile</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    @include('utils.logout2', ['class' => 'nav-link'])
                                </li>

                                <li class="nav-item text-center">
                                    <span> {{ $contact->name }} </span>
                                </li>

                                <li class="nav-item text-center hide">
                                    <a class="nav-link btn btn-theme"
                                       style="color: #f4f4f4; border-radius: 28px; margin: 12px auto; padding: 4px; width: 210px; font-size: 13px;"
                                       href="http://www.giftingnetwork.com">
                                        <span> Switch to Classic Website </span>
                                    </a>
                                </li>

                        </ul>
                    </div>

                    {{-- Desktop --}}
                    <div class="d-none d-md-block">
                        <ul class="navbar-nav ml-auto nbnav-desktop">

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

                                    @if($custom->feature->CONTRIBUTION)
                                        <a class="dropdown-item" href="{{ route('contribute') }}">
                                            <div>
                                                <div class="title">{{ $custom->text->MAKE_A_GIFT }}</div>
                                                <div class="subtitle">Add funds to your account</div>
                                            </div>
                                        </a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('grant-create') }}">
                                        <div>
                                            <div class="title">{{ $custom->text->MAKE_A_GRANT }}</div>
                                            <div class="subtitle">Send funds to non-profit</div>
                                        </div>
                                    </a>

                                    @if($custom->feature->RECENT_CONTRIBUTIONS)
                                        <a class="dropdown-item" href="{{ route('transactions') }}">{{$custom->text->RECENT_CONTRIBUTIONS}}</a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('my-cart') }}">{{ \App\Models\GrantForm::cartLabel() }} {{ $badgeCount ? '(' . $badgeCount . ')': '' }}</a>

                                    @if($custom->feature->CHARITABLE_CATALOG)
                                        <a class="dropdown-item" href="{{ route('charitable-catalog') }}">{{ $custom->text->CHARITABLE_CATALOG }}</a>
                                    @endif

                                    @if($custom->feature->APP_PROFILE)
                                        <a class="dropdown-item" href="{{ route('profile') }}">My Profile</a>
                                    @endif

                                    <div class="dropdown-divider"></div>
                                    @include('utils.logout', ['class' => 'dropdown-item'])
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            @endif
        </div>

    </nav>


    <div id="nav-powered">
        <a class="nav-powered-view" href="//giftingnetwork.com/" target="_blank"><img src="/ma/images/logo_xs.png">Powered by GiftingNetwork</a>
    </div>

</header>

@include('utils._form_logout')

<script>
    $(window).resize(function(){ poweredBy(); });
    $(function(){ poweredBy(); });
</script>

<script>
    $(window).resize(function(){
        console.log("window.screen.width" + window.screen.width);
        console.log("(window).width()" + $(window).width());
        console.log("(document).width()" + $(document).width());
        console.log("window.innerWidth" + window.innerWidth);
    });
</script>
