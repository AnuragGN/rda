@include('errors.error', ['code' => 404, 'message' => 'Not Found: This page could not be found - ' . url()->current() ])


{{--@extends('errors::minimal')--}}

{{--@section('title', __('Not Found'))--}}
{{--@section('code', '404')--}}
{{--@section('message', __('Not Found'))--}}
