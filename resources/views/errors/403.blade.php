@include('errors.error', ['code' => 403, 'message' => 'Forbidden: This page is not accessible'])


{{--@extends('errors::minimal')--}}

{{--@section('title', __('Forbidden'))--}}
{{--@section('code', '403')--}}
{{--@section('message', __($exception->getMessage() ?: 'Forbidden'))--}}
