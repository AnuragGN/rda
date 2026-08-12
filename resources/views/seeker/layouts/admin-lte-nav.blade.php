<?php
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
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

    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('profile') }}" class="nav-link">{{ $contact->name }}</a>
        </li>
    </ul>

</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @include( \App\Models\ClientInfo::clientViewFor('seeker.layouts.client-logo'))

    <!-- Sidebar -->
    <div id="id_main_side_menu" class="sidebar">
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
                    <a href="{{route("gs-home")}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item HIDE">
                    <a href="{{route('gs-org-profile')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Organization</p>
                    </a>
                </li>


                <li class="nav-item has-treeview {{ request()->is('m/gs/org/*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <p class="text-uppercase">
                            Edit Organization
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('gs-org-edit-profile')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Organization</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-staff-management')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Staff Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-organization-story')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Organization Story</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-interest-areas')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Interest Areas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-budget')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Budget</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-goals')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Goals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-board-members')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Board Members</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-tax-information')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Tax Information</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-documentation')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Documentation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-population-served')}}" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Population Served</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('gs-org-certifications')}}" class="nav-link">
                                <i class="nav-icon fa fa-check-square"></i>
                                <p>Certifications</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">ACCOUNT</li>
                <li class="nav-item">
                    <a href="{{route('profile')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Edit Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('gs-assistant')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Edit Assistant</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('change-password-form')}}" class="nav-link">
                        <i class="nav-icon far fa-edit"></i>
                        <p>Change Password</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase">MISCELLANEOUS</li>
                <li class="nav-item pb-5">
                    <a href="javascript:void(0);" class="nav-link" onclick="onLogout()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>


                <div class="hide">
                    <li class="nav-header text-uppercase">X-Account</li>
                    <li class="nav-item">
                        <a href="{{route('gs-account-contact-profile')}}" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>X-Contact Profile </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('gs-account-my-profile')}}" class="nav-link">
                            <i class="nav-icon far fa-id-badge"></i>
                            <p>X-My Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('gs-account-info')}}" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>X-Account Info</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase">X-MISCELLANEOUS</li>
                    <li class="nav-item pb-5">
                        <a href="javascript:void(0);" class="nav-link" onclick="onLogout()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            X-Logout
                        </a>
                    </li>
                </div>

            </ul>
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
