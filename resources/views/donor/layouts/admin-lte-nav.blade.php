<?php
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
{{--if (!isset($errorPage)) {--}}
    $errorPage = false;
    $badgeCount = \App\Models\GrantItem::countCartItems();
{{--} else {--}}
{{--    $badgeCount = 0;--}}
{{--}--}}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
    </ul>

    @if(\App\Helpers\GnUtils::isEmulationMode())
        <div class="navbar-emulation" style="">
            <span>EMULATION MODE</span> <small> [{{\App\Helpers\GnUtils::getSuperSessionContactName()}}]</small>
        </div>
    @endif

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link nav-link-cart" href="{{ route('my-cart') }}" style="position: relative">
                @if ($badgeCount)
                    <div id="jsid-sm-badge" class="icon-badge">{{$badgeCount}}</div>
                @endif
                <i class="fas fa-shopping-cart"></i>
            </a>
        </li>
        @if(\App\Models\ClientInfo::isFFP())
        <li class="nav-item">
            <a class="nav-link nav-link-cart" onclick="getNotilist();" 
            style="position: relative;cursor: pointer;">
                <div class="icon-badge" id="notification-count">0</div>
                <i data-count="0" class="icon fas fa-bell notification-icon"></i>
               <input type="hidden" id="total_notification" value="0">
            </a>
            <div class="dropdown-container" id="notification_list" style="display:none;">
               <ul class="notification-menu" id="divNotificationList">

              </ul>
          </div>
        </li>
        @endif
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('profile') }}" class="nav-link">{{ $contact->name }}</a>
        </li>
    </ul>
</nav>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 gn-main-sidebar">
    <!-- Brand Logo -->
    @include( \App\Models\ClientInfo::clientViewFor('layouts.sidebar-logo'))

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex d-block d-sm-none">
            <div class="image" style="font-size: 22px">
                <i class="far fa-user"></i>
            </div>
            <div class="info">
                <a href="{{ route('profile') }}" class="d-block">{{ $contact->name }}</a>
            </div>
        </div>

        {{--<div class="pt-2"></div>--}}
        <!-- Sidebar Menu -->
        <nav class="mt-4">

            {{--<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">--}}
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent2 nav-compact nav-legacy nav--flatv-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{route('donor-home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item hide">
                    <a href="{{route('agency-funds')}}" class="nav-link">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>Funds</p>
                    </a>
                </li>

                <li class="nav-item has-treeview hide">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Test Items <i class="fas fa-angle-right right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('gs-org-edit-profile')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-edit-profile')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--<li class="nav-header" style="text-transform: uppercase">DAF</li>--}}

                @if($custom->feature->CONTRIBUTION)
                    <li class="nav-item">
                        <a href="{{route('contribute')}}" class="nav-link">
                            <i class="nav-icon fas fa-donate"></i>
                            <p>{{ $custom->text->MAKE_A_GIFT }}
                                <span class="hide"> Add funds to your account</span>
                            </p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('grant-create')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>{{ $custom->text->MAKE_A_GRANT }}</p>
                    </a>
                </li>

                @if(\App\Models\ClientInfo::isCCT() || \App\Models\ClientInfo::isNTC())
                    <li class="nav-item">
                        <a href="{{route('recurring-grants')}}" class="nav-link">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>Recurring Grants</p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->INVESTMENTS and \App\Models\ClientInfo::isNIF())
                    <li class="nav-item">
                        <a href="{{route('get-investments')}}" class="nav-link">
                            <i class="nav-icon fas fa-donate"></i>
                            <p>Invest</p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->RECENT_CONTRIBUTIONS)
                    <li class="nav-item">
                        <a href="{{route('transactions')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p><small2>{{$custom->text->RECENT_CONTRIBUTIONS}}</small2></p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->MY_STATEMENTS)
                    <li class="nav-item">
                        <a href="{{route('my-statements')}}" class="nav-link">
                            <i class="nav-icon far fa-file"></i>
                            <p>{{ $custom->text->FUND_STATEMENTS }}</p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->FUND_ADVISORS)
                    <li class="nav-item">
                        <a href="{{route('fund-advisors')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Fund Advisors</p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->MY_DOCUMENTS)
                    <li class="nav-item">
                        <a href="{{route('my-documents')}}" class="nav-link">
                            <i class="nav-icon far fa-file"></i>
                            <p>{{ $custom->text->FUND_DOCUMENTS }}</p>
                        </a>
                    </li>
                @endif

                @if(\App\Models\ClientInfo::isHGA())
                    <li class="nav-item">
                        <a href="{{route('forms')}}" class="nav-link">
                            <i class="nav-icon far fa-file"></i>
                            <p>Forms</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('my-cart')}}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>{{\App\Models\GrantForm::cartLabel()}} {{ $badgeCount ? ' (' . $badgeCount . ')': '' }}</p>
                    </a>
                </li>

                @if(\App\Models\ClientInfo::isCCT())
                    <li class="nav-header" style="padding-left: 1rem"><small>CATALOG</small></li>
                    <li class="nav-item">
                        <a href="{{route('content.programs')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>CCT Initiatives</p>
                        </a>
                    </li>
                @endif

                @if($custom->feature->CHARITABLE_CATALOG)
                    {{--<hr class="hr-divider">--}}
                    <li class="nav-header" style="padding-left: 1rem"><small>CATALOG</small></li>

                    <li class="nav-item">
                        <a href="{{route('charitable-catalog')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>{{ $custom->text->CHARITABLE_CATALOG }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('organization-matches')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Organization Matches</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('programs-matches')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Program Matches</p>
                        </a>
                    </li>

                @endif

                @if(false)
                    {{--@if($custom->feature->CONTRIBUTION)--}}
                    {{--<a class="dropdown-item" href="{{ route('contribute') }}">--}}
                    {{--<div>--}}
                    {{--<div class="title">{{ $custom->text->MAKE_A_GIFT }}</div>--}}
                    {{--<div class="subtitle">Add funds to your account</div>--}}
                    {{--</div>--}}
                    {{--</a>--}}
                    {{--@endif--}}

                    {{--<a class="dropdown-item" href="{{ route('grant-create') }}">--}}
                        {{--<div>--}}
                            {{--<div class="title">Make a Grant</div>--}}
                            {{--<div class="subtitle">Send funds to non-profit</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}

                    {{--@if($custom->feature->RECENT_CONTRIBUTIONS)--}}
                        {{--<a class="dropdown-item" href="{{ route('transactions') }}">{{$custom->text->RECENT_CONTRIBUTIONS}}</a>--}}
                    {{--@endif--}}

                    {{--<a class="dropdown-item" href="{{ route('my-cart') }}">My Cart {{ $badgeCount ? '(' . $badgeCount . ')': '' }}</a>--}}

                    @if($custom->feature->CHARITABLE_CATALOG)
                        <a class="dropdown-item" href="{{ route('charitable-catalog') }}">{{ $custom->text->CHARITABLE_CATALOG }}</a>
                    @endif
                @endif


                @if($custom->feature->FUND_N_POOL_PERFORMANCE)
                    <li class="nav-header" style="padding-left: 1rem"><small>PERFORMANCE</small></li>

                    <li class="nav-item">
                        <a href="{{route('donor-pool-performance', ['id' => '4001'])}}" class="nav-link">
                            <i class="nav-icon far fa-chart-bar"></i>
                            <p>Pool Performance</p>
                        </a>
                    </li>
                @endif

                @if(\App\Models\ClientInfo::isHGA())
                    <li class="nav-header" style="padding-left: 1rem"><small>INVESTMENTS</small></li>
                    @if($custom->feature->INVESTMENTS)
                        <li class="nav-item">
                            <a href="{{route('get-investments')}}" class="nav-link">
                                <i class="nav-icon fas fa-donate"></i>
                                <p>{{ $custom->text->INVESTMENTS }}</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{route('investment-fund-performance')}}" class="nav-link">
                            <i class="nav-icon far fa-file"></i>
                            <p>Investment Performance</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('research-investment-options')}}" class="nav-link">
                            <i class="nav-icon far fa-file"></i>
                            <p>Research Investment Options</p>
                        </a>
                    </li>
                @endif

                {{--<hr class="hr-divider">--}}
                <li class="nav-header" style="padding-left: 1rem"><small>AI TOOLS</small></li>
                <li class="nav-item">
                    <a href="{{ route('chatbot.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-robot"></i>
                        <p>AI Assistant</p>
                    </a>
                </li>

                <li class="nav-header" style="padding-left: 1rem"><small>ACCOUNT & PROFILE</small></li>

                <li class="nav-item">
                    <a href="{{route('profile')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('profile-interests')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Interest Profile</p>
                    </a>
                </li>

                @if(!(\App\Models\ClientInfo::isHGA()))
                    @if($custom->feature->INVESTMENTS)
                        <li class="nav-item">
                            <a href="{{route('get-investments')}}" class="nav-link">
                                <i class="nav-icon fas fa-donate"></i>
                                <p>{{ $custom->text->INVESTMENTS }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-item">
                    <a href="{{route('change-password-form')}}" class="nav-link">
                        <i class="nav-icon far fa-edit"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('ticket')}}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>Tickets
                            @if(\App\Models\Ticket::OpenTicketCount() > 0)
                                <span class="">({{ \App\Models\Ticket::OpenTicketCount() }})</span>
                            @endif
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('donor-notifications')}}" class="nav-link">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifications</p>
                    </a>
                </li>
                {{--<li class="nav-header" style="padding-left: 1rem"><small>MISCELLANEOUS</small></li>--}}
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link" onclick="onLogout()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
            <br>
            <br>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });
</script>
@include('bell_notification_js');
