<?php
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
if (!isset($errorPage)) $errorPage = false;
if (!isset($contactId) || !$contactId) $contactId = \App\Models\Contact::sessionContactId();

$sponsors = \App\Models\FaSponser::getDafSponsors();

// Initialize $contactTypeId to handle the result
$contactTypeId = null;
$contactType = null;

if (isset($contactId) && $contactId) {
    // Fetch the contact_type_id from the contact_type_contact table
    $contactTypeContact = \App\Models\ContactTypeContact::where('contact_id', $contactId)->first();

    if ($contactTypeContact) {
        $contactTypeId = $contactTypeContact->contact_type_id;
    } else {
        // Handle the case where no matching record is found
        $contactTypeId = null;
    }
}

// Fetch the contact_type from the contact_type table using $contactTypeId
if ($contactTypeId) {
    $contactType = \App\Models\ContactType::find($contactTypeId);
}
?>
<style>
    .profile-picture {
        width: 40px; / Adjust size as needed /
        height: 40px; / Adjust size as needed /
        border-radius: 50%;
        object-fit: cover; / Ensures the image covers the circle without distortion /
        margin-right: 2px; / Space between image and text /
        margin-top: -5px; /*Adjust the vertical position of the image; negative value moves it up*/
    }

.menu-item {
    position: relative;
}

/* Submenu as dropdown */
.submenu {
    position: absolute;          /* 🔑 KEY FIX */
    top: 100%;                   /* Open below button */
    left: 0;
    z-index: 9999;

    list-style: none;
    padding: 6px 0;
    margin: 6px 0 0 0;

    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);

    min-width: 220px;
    display: none;
}

.submenu li a {
    display: block;
    padding: 10px 14px;
    color: #111827;
    text-decoration: none;
    font-size: 14px;
}

.submenu li a:hover {
    background: #f3f4f6;
}

/* Show submenu */
.menu-item.active .submenu {
    display: block;
}

</style>
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

        <li class="menu-item nav-item position-relative">
            <a href="javascript:void(0);"
                style="box-shadow: 0 2px 6px rgba(0,0,0,0.08); padding: 6px 18px; font-weight: bold; margin-top: 4px;"
                class="btn btn-accent btn-sm mr-2 ml-2"
                onclick="toggleSponsorMenu(event)">
                Open a DAF Account
            </a>

            <ul class="submenu" id="daf-sponsor-menu">
                @foreach($sponsors as $sponsor)
                    <li data-sponsor-id="{{ $sponsor->id }}" data-sponsor-name="{{ $sponsor->name }}" onclick="submitSponsor(this)" >
                        <a href="javascript:void(0);">
                            {{ $sponsor->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <form class="m-0 p-0" style="width:100%;" id="dafForm" action="{{ route('post-agency-create-daf-account') }}" method="post" target="_blank">
                @csrf     
                <input type="hidden" name="sponsor_id" id="sponsor_id">
            </form>
        </li>

        <li class="nav-item position-relative" style="z-index:1051; display:none;">
            <a href="javascript:void(0);" id="daf-toggle-btn" class="btn btn-accent btn-sm mr-2 ml-2" style="box-shadow: 0 2px 6px rgba(0,0,0,0.08); padding: 6px 18px; font-weight: bold;margin-top: 4px;" onclick="toggleDafForm(event)">
                Open a DAF Account
            </a>
            <div id="daf-dropdown-form" style="display:none; position:absolute; right:0; top:110%; min-width:440px;
            background:#e6f4f7; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.12); padding:16px 18px;">

                
                <form class="m-0 p-0" style="width:100%;" id="daf-form" action="{{ route('post-agency-create-daf-account') }}" method="post" target="_blank">
                    @csrf    
                    <div class="form-row align-items-center" style="display:flex; flex-wrap:nowrap;">
                        <label for="daf-sponsor" class="mb-0 mr-2" style="white-space:nowrap; font-weight:500;">
                            DAF Sponsor
                        </label>

                        <select class="form-control mr-2" id="daf-sponsor" name="sponsor_id" required>
                            <option value="">Select DAF Sponsor</option>
                            @foreach($sponsors as $sponsor)
                                <option value="{{ $sponsor->id }}">
                                    {{ $sponsor->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            Go
                        </button>
                    </div>
                </form>
            </div>
        </li>

        @if(\App\Models\ClientInfo::isFFP())
            <li class="nav-item hide">
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
            <a href="{{ route('profile') }}" class="nav-link">
                @if($contact->photo_url)
                    <img src="{{ $contact->photo_url }}" class="profile-picture" alt="{{ $contact->name }}" style="width: 40px;height: 40px;border-radius: 50%;object-fit: cover; margin-right: 2px;margin-top: -5px;">
                @endif
                {{ $contact->name }} 
                @if($contactType->contact_type == 'Agency Fund Holder')
                    Advisor
                @else
                    {{ $contactType->contact_type }}
                @endif
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: {{\App\Models\FaPartner::getClientBackgroundColor()}}">
    <!-- Brand Logo -->
    <a href="{{ route('agency-dashboard') }}" class="brand-link" style="background: #fff">
        <img src="{{\App\Models\FaPartner::getClientHeaderLogo()}}" alt="GN" class="brand-image">
    </a>
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
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent2 nav-compact nav-legacy nav--flatv-flat" data-widget="treeview" role="menu" data-accordion="false">
		<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('agency-dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item" style="Display:none;">
                    <a href="{{route('agency-home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item hide">
                    <a href="{{route('agency-services')}}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>Services</p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="{{route('agency-daf-accounts')}}" class="nav-link">
                        <i class="nav-icon fa fa-user-tie"></i>
                        <p>DAF Applications</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('agency-ticket')}}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>Service Tickets
                            @if(\App\Models\Ticket::OpenTicketCount() > 0)
                                <span class="badge badge-info btn-accent">{{ \App\Models\Ticket::OpenTicketCount() }}</span>
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
                    <a href="{{route('change-password-form')}}" class="nav-link">
                        <i class="nav-icon far fa-edit"></i>
                        <p>Change Password</p>
                    </a>
		        </li>
                <li class="nav-item">
                        <a href="{{route('agency-preferences')}}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Preferences</p>
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

    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });
  
    function toggleSponsorMenu(event) 
    {
        event.preventDefault();
        event.stopPropagation(); 

        const menuItem = event.target.closest('.menu-item');

        document.querySelectorAll('.menu-item').forEach(item => {
            if (item !== menuItem) {
                item.classList.remove('active');
            }
        });
        menuItem.classList.toggle('active');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.menu-item')) {
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
        }
    });


    function submitSponsor(el)
    {
        const sponsorId   = el.dataset.sponsorId;
        const sponsorName = el.dataset.sponsorName;

        $.confirm({
            columnClass: 'medium',
            title: '',
            content: `You are about to create a Donor-Advised Fund (DAF) with <b>${sponsorName}</b>. Do you want to continue?`,
            buttons: {
                no: {
                    text: 'No',
                    btnClass: 'btn-light'
                },
                yes: {
                    text: 'Yes',
                    btnClass: 'btn-accent',
                    action: function () {
                        $('#sponsor_id').val(sponsorId);
                        $('#dafForm').submit();
                    // $('#daf-sponsor-menu').hide();
                    }
                }
            }
        });
    }
</script>
@include('bell_notification_js');
