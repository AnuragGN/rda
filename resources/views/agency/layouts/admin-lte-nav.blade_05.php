<?php
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
if (!isset($errorPage)) $errorPage = false;
?>
{{--Navbar--}}

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

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3 hide">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto"> 

        @if(\App\Models\ClientInfo::isFFP() || \App\Models\ClientInfo::isGNA())

        <li class="nav-item position-relative" style="z-index:1051;">
            <a href="javascript:void(0);" id="daf-toggle-btn" class="btn btn-accent btn-sm mr-2 ml-2" style="box-shadow: 0 2px 6px rgba(0,0,0,0.08); padding: 6px 18px; font-weight: bold;margin-top: 4px;" onclick="toggleDafForm(event)">
                Open a DAF Account
            </a>
            <div id="daf-dropdown-form" style="display:none; position:absolute; right:0; top:110%; min-width:440px; background:#e6f4f7; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.12); padding:16px 18px;">
                <form class="m-0 p-0" style="width:100%;" id="daf-form" action="/open-daf-account" method="get" onsubmit="return handleDafSubmit(event)">
                    <div class="form-row align-items-center" style="display:flex; flex-wrap:nowrap;">
                        <label for="daf-sponsor" class="mb-0 mr-2" style="white-space:nowrap; font-weight:500;">Sponsor</label>
                        <select class="form-control mr-2" id="daf-sponsor" name="sponsor_id" style="min-width:120px;" required>
                            <option value="">Select Sponsor</option>
                            @foreach(config('charities', []) as $charityKey => $charityVal)
                                <option value="{{ $charityVal['charity_id'] }}">
                                    {{ $charityVal['charity_name'] }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Go</button>
                    </div>
                </form>
            </div>
        </li>

        <li class="nav-item ">
            <a class="nav-link nav-link-cart" onclick="getNotilist();" 
            style="position: relative;cursor: pointer;">
                <div class="icon-badge" id="notification-count">0</div>
                <i data-count="0" class="icon fas fa-bell notification-icon"></i>
               <input type="hidden" id="total_notification" value="0">
            </a>

            <div class="dropdown-container" id="notification_list" style="display: none;">
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
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
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

        <!-- Sidebar Menu -->
        <nav class="mt-4">

            {{--<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">--}}
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent2 nav-compact nav-legacy nav--flatv-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{route('agency-home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('agency-dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>New Dashboard</p>
                    </a>
                </li>

                <li class="nav-item hide">
                    <a href="{{route('agency-services')}}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>Services</p>
                    </a>
                </li>

                

                <li class="nav-item">
                    <a href="{{route('agency-ticket')}}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>Service Tickets
                            @if(\App\Models\Ticket::OpenTicketCount() > 0)
                                <span class="badge badge-info">{{ \App\Models\Ticket::OpenTicketCount() }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('agency-recommendation')}}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Recommendation</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('agency-client')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Clients</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{route('report-home')}}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Reports</p>
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{route('authentication.page')}}" class="nav-link">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>Events</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('agency-notifications')}}" class="nav-link">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifications</p>
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

                {{--@include('agency.layouts.admin-lte-nav-pools')--}}

                <li class="nav-header" style="padding-left: 1rem"><small>ACCOUNT & PROFILE</small></li>
                <li class="nav-item">
                    <a href="{{route('profile')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('agency-preferences')}}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Preferences</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('change-password-form')}}" class="nav-link">
                        <i class="nav-icon far fa-edit"></i>
                        <p>Change Password</p>
                    </a>
                </li>

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
function toggleDafForm(e) {
    e.stopPropagation();
    var form = document.getElementById('daf-dropdown-form');
    if (form.style.display === 'block') {
        form.style.display = 'none';
        document.removeEventListener('click', closeDafFormOnClick);
        return;
    }
    form.style.display = 'block';
    setTimeout(function() {
        document.addEventListener('click', closeDafFormOnClick);
    }, 0);
}
function closeDafFormOnClick(e) {
    var form = document.getElementById('daf-dropdown-form');
    var btn = document.getElementById('daf-toggle-btn');
    if (!form.contains(e.target) && e.target !== btn) {
        form.style.display = 'none';
        document.removeEventListener('click', closeDafFormOnClick);
    }
}
function handleDafSubmit(e) {
    e.preventDefault();
    var sponsor = document.getElementById('daf-sponsor').value;
    if (!sponsor) {
        document.getElementById('daf-sponsor').focus();
        return false;
    }
    // Redirect to the agency-charity route with the selected sponsor id
    window.location.href = '/m/agency/charity/daf/' + encodeURIComponent(sponsor);
    return false;
}
</script>
</aside>
<script>
    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });
</script>
@include('bell_notification_js');