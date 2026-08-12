<!DOCTYPE html>
<html>

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
    <link href="/ma/plugins/fontawesome/css/all.min.css" rel="stylesheet" >
    <link href="/ma/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/ma/plugins/jquery-confirm/dist/jquery-confirm.min.css" rel="stylesheet">
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <link href="/ma/plugins/jqvmap/jqvmap.min.css" rel="stylesheet">
    <link href="/ma/adminlte/css/adminlte.min.css" rel="stylesheet">
    <link href="/ma/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" rel="stylesheet">
    <link href="/ma/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="/ma/plugins/summernote/summernote-bs4.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link href="/ma/css/typeahead.css" rel="stylesheet">
    <link href="/ma/css/styles.css" rel="stylesheet">
    <link href="/ma/css/charts.css" rel="stylesheet">
    <link href="/ma/css/catalog.css" rel="stylesheet">
    <link href="/ma/css/profile.css" rel="stylesheet">
    <link href="/ma/css/media.css" rel="stylesheet">
    <link href="/ma/css/colors.css" rel="stylesheet">

    <link href={{ \App\Models\ClientInfo::clientCss("styles.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("media.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("colors.css") }} rel="stylesheet">

    <link href="/ma/agency/css/styles.css" rel="stylesheet">
    <link href="/ma/agency/css/media.css" rel="stylesheet">
    <link href="/ma/agency/css/colors.css" rel="stylesheet">

    {{-- Scripts --}}
    {{--<script src="{{url('/ma/plugins/jquery/jquery.min.js')}}"></script>--}}
    <script src="/ma/javascripts/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
    
    {{--@include('utils.google-analytics')--}}

</head>

{{--<body class="hold-transition skin-blue sidebar-mini">--}}
<body class="sidebar-mini layout-fixed layout-navbar-fixed">


@include('support_staff.layouts.flash-box')

<div class="wrapper">

    @include('support_staff.layouts.admin-lte-nav')
    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">

        @include('support_staff.layouts.session-status')

        @yield('content')

    </div>

    @include(\App\Models\ClientInfo::clientViewFor('support_staff.layouts.main-footer'))

</div>

@yield('footer-scripts')

@include('utils._form_logout')

@include('support_staff.layouts.main-scripts')

</body>

</html>
