<?php
if (!isset($container)) $container = 'container';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{\App\Models\ClientInfo::name()}}</title>
    <meta name="description" content="{{\App\Models\ClientInfo::description()}}">
    <meta name="author" content="alkeshkumar@gmail.com">

    <!-- Favicons -->
    <link rel='icon' href={{\App\Models\ClientInfo::favicon()}} type='image/x-icon'/ >

    <!-- CSS -->
    {{--<link href="/ma/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/ma/plugins/jquery-confirm/dist/jquery-confirm.min.css" rel="stylesheet">
    <link href="/ma/plugins/fontawesome/css/all.min.css" rel="stylesheet" >
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <link href="/ma/plugins/jqvmap/jqvmap.min.css" rel="stylesheet">
    <link href="/ma/adminlte/css/adminlte.min.css" rel="stylesheet">
    <link href="/ma/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" rel="stylesheet">
    <link href="/ma/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="/ma/plugins/summernote/summernote-bs4.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    {{-- common for all apps --}}
    <link href="/ma/css/typeahead.css" rel="stylesheet">
    <link href="/ma/css/styles.css" rel="stylesheet">
    <link href="/ma/css/charts.css" rel="stylesheet">
    <link href="/ma/css/catalog.css" rel="stylesheet">
    <link href="/ma/css/profile.css" rel="stylesheet">
    <link href="/ma/css/media.css" rel="stylesheet">
    <link href="/ma/css/colors.css" rel="stylesheet">

    {{-- all specific styles --}}
    <link href="/ma/seeker/css/styles.css" rel="stylesheet">
    <link href="/ma/seeker/css/media.css" rel="stylesheet">
    <link href="/ma/seeker/css/colors.css" rel="stylesheet">

    {{-- client customization --}}
    <link href={{ \App\Models\ClientInfo::clientCss("styles.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("media.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("colors.css") }} rel="stylesheet">

    {{-- Scripts --}}
    <script src="{{url('/ma/plugins/jquery/jquery.min.js')}}"></script>

    {{--@include('utils.google-analytics')--}}

</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed">

{{-- TODO: GTM --}}
<!-- Google Tag Manager (noscript) -->
{{--<noscript>--}}
    {{--<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MMNKX3M"--}}
            {{--height="0" width="0" style="display:none;visibility:hidden"></iframe>--}}
{{--</noscript>--}}
<!-- End Google Tag Manager (noscript) -->

<div class="wrapper">

    @include('seeker.layouts.flash-box')
    @include('seeker.layouts.admin-lte-nav')
    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">

        @include('seeker.layouts.session-status')

        @yield('content')

    </div>

    @include(\App\Models\ClientInfo::clientViewFor('seeker.layouts.main-footer'))

</div>

@yield('footer-scripts')

@include('utils._form_logout')

@include('seeker.layouts.main-scripts')

</body>

</html>
