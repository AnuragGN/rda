<?php
if (!isset($container)) $container = 'container';
?>

<!DOCTYPE html>
<html>

@include('demo.main-head')

<body class="gn-body">

<div class="gn-wrapper">

    <div class="gn-content">

        @include('donor.layouts.flash-box')
        @include('demo.main-nav')

        <div class="{{$container}}">
            @yield('content')
        </div>

    </div>

    <!-- FOOTER -->
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-12">--}}
                {{--<div class="hga-copyright-row">© 2019 HIGHGROUND ADVISORS. ALL RIGHTS RESERVED.</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}


    <div class="gn-footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="hga-copyright-row">© 2020 HIGHGROUND ADVISORS. ALL RIGHTS RESERVED.</div>
                </div>
            </div>
        </div>
        <div class="gn-powered-by text-center">
            <a href="//giftingnetwork.com/" target="_blank">
                <img src="/images/gn-logo-sm.png">Powered by GiftingNetwork</a>
        </div>
    </div>


</div>

@yield('footer-scripts')

<div class="scripts">
    {{--<script src="/bootstrap/js/tether.min.js"></script>--}}
    <script src="/javascripts/popper.js"></script>
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{--<script src="/bootstrap-material-design/js/bootstrap-material-design.min.js"></script>--}}
    {{--<script src="/jquery-confirm/dist/jquery-confirm.min.js"></script>--}}
    {{--<script src="/javascripts/handlebars.js"></script>--}}
    {{--<script src="/javascripts/typeahead.bundle.min.js"></script>--}}

    <script type="text/javascript" src="/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="/daterangepicker/daterangepicker.js"></script>
    {{--<script src="/js/typeahead.bundle.min.js"></script>--}}
    {{--<script src="/js/jquery.splendid.textchange.js"></script>--}}
    {{--<script src="/js/jquery.textchange.min.js"></script>--}}

    {{--<script src="/javascripts/main.js"></script>--}}
    <script src="/javascripts/demo.js"></script>
    {{--<script src="/javascripts/search.js"></script>--}}
    {{--<script src="/javascripts/field-formats.js"></script>--}}
    {{--<script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>--}}

</div>

</body>

</html>
