@extends('donor.layouts.main')

<?php
$user = auth()->user();
$name = $user ? $user->username : 'none';
?>
@section('content')

    <h1> WELCOME BACK!!! {{ $name }}</h1>

@endsection
