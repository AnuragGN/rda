<!DOCTYPE html>
{{--{{ commented for authorize.net sandbox}}--}}
{{--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">--}}
{{--<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">--}}

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<title>{{ config('app.name', 'GiftingNetwork') }}</title>--}}
    <title>{{ \App\Models\ClientInfo::name() }}</title>

    <!-- Meta -->
    <meta name="description" content={{ \App\Models\ClientInfo::description() }}>
    <meta name="author" content="alkeshkumar@gmail.com">
    <meta name="robots" content="noindex" />

    <!-- social media meta tags start -->
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="@yield('meta-title', \App\Models\ClientInfo::name())" />
    <meta property="og:description"   content="@yield('meta-description', \App\Models\ClientInfo::name())" />
    <meta name="twitter:title"        content="@yield('meta-title', \App\Models\ClientInfo::name())"/>
    <meta name="twitter:description"  content="@yield('meta-description', \App\Models\ClientInfo::name())"/>
    @hasSection('meta-image')
        <meta property="og:image"         content="@yield('meta-image')" />
        <meta name="twitter:image"        content="@yield('meta-image')"/>
    @endif
    <!-- social media meta tags end -->

    <!-- Favicons -->
    <link rel='icon' href={{\App\Models\ClientInfo::favicon()}} type='image/x-icon'/ >

    <!-- CSS -->
    {{--<style class="anchorjs"></style>--}}
    <link href="/ma/plugins/fontawesome/css/all.css" rel="stylesheet" >
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

    @if((isset($external) and $external) or \App\Models\ClientInfo::isGMF())
        <link href={{ \App\Models\ClientInfo::clientCss("external.css") }} rel="stylesheet">
    @endif

    <link href={{ \App\Models\ClientInfo::clientCss("styles.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("media.css") }} rel="stylesheet">
    <link href={{ \App\Models\ClientInfo::clientCss("colors.css") }} rel="stylesheet">

    @if(\App\Helpers\GnUtils::isAgencySession())
        <link href="/ma/agency/css/styles.css" rel="stylesheet">
        <link href="/ma/agency/css/media.css" rel="stylesheet">
        <link href="/ma/agency/css/colors.css" rel="stylesheet">
    @endif
    {{--<link href="/ma/css/btns.css" rel="stylesheet">--}}

    @if(\App\Models\ClientInfo::isJCF())
        <link href="https://use.typekit.net/uhf7mie.css">
    @endif

    {{-- Scripts --}}
    {{--<script src="{{url('javascripts/jquery-3.6.0.min.js')}}"></script>--}}
    <script src="/ma/javascripts/jquery-3.6.0.min.js" type="text/javascript"></script>

    @include(\App\Models\ClientInfo::clientViewFor('layouts.client-head'))
    @include('utils.google-analytics')
</head>
