@include('errors.error', ['code' => 429, 'message' => 'Too Many Requests: Please retry after some time'])


{{--@extends('errors::minimal')--}}

{{--@section('title', __('Too Many Requests'))--}}
{{--@section('code', '429')--}}
{{--@section('message', __('Too Many Requests'))--}}
