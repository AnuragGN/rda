<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GiftingNetwork') }}</title>

    <!-- Meta -->
    <meta name="description" content="No desciption">
    <meta name="author" content="alkeshkumar@gmail.com">
    <!-- Favicons -->
    <link rel='icon' href="/ma/images/favicon.ico" type='image/x-icon'>

    <!-- CSS -->
    <link href="/ma/plugins/fontawesome/css/all.css" rel="stylesheet">
    <link href="/ma/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/ma/plugins/jquery-confirm/dist/jquery-confirm.min.css" rel="stylesheet">
    <link href="/ma/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">--}}

    {{--<link href="/css/typeahead.css" rel="stylesheet">--}}
    {{--<link href="/css/styles.css" rel="stylesheet">--}}
    {{--<link href="/css/media.css" rel="stylesheet">--}}
    {{--<link href="/css/colors.css" rel="stylesheet">--}}

    <link href="/ma/css/registration.css" rel="stylesheet">

    {{--<link href="/css/btns.css" rel="stylesheet">--}}

    {{-- Scripts --}}
    <script src="{{url('javascripts/jquery-3.6.0.min.js')}}"></script>

    {{--@include('utils.google-analytics')--}}

    <!-- Start of  Zendesk Widget script -->
    {{--<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=f9195565-2534-4680-aeac-dde32fedfe8e"> </script>--}}
    <!-- End of  Zendesk Widget script -->

</head>
