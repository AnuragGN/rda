<?php
if (!isset($container)) $container = 'container';

// if (!isset($navBar)) {
//    $navBar = \App\Helpers\GnUtils::getUserView('layouts.admin-lte-nav');
// }
$navBar = 'donor.layouts.admin-lte-nav';
?>

<!DOCTYPE html>
<html>

@include('donor.layouts.main-head')

<body class="sidebar-mini layout-fixed layout-navbar-fixed">

@include('donor.layouts.flash-box')

<div class="wrapper">

    @include($navBar)
    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">

        @include('donor.layouts.session-status')

        @yield('content')

    </div>

    @include(\App\Models\ClientInfo::clientViewFor('layouts.main-footer'))

</div>

@yield('footer-scripts')

@include('utils._form_logout')

@include('donor.layouts.main-scripts')

@if($custom->feature->SESSION_TIMEOUT)
{{--@if(\App\Models\ClientInfo::isCCT() or \App\Models\ClientInfo::isGNA())--}}
    @include("common.modal-logout-timer")
@endif

</body>

</html>
