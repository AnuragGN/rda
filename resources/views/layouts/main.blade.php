<?php
if (!isset($container)) $container = 'container';

if (!isset($navbar)) $navbar = 'layouts.main-nav';
?>

<!DOCTYPE html>
<html>

@include('layouts.main-head')

<body class="gn-body">

<div class="gn-wrapper">

    <div class="gn-content">

        @include('layouts.flash-box')
        @include($navbar)

        <div class="{{$container}}">
            @yield('content')
        </div>

    </div>

    @include('' . \App\Models\ClientInfo::clientViews() . 'layouts.main-footer', ['fullWidthFooter' => true])

</div>

@yield('footer-scripts')

@include('layouts.main-scripts')

</body>

</html>
