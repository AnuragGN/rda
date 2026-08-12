<?php
$dafRegistrationPage = true;
?>
<!DOCTYPE html>
<html>

@include('donor.layouts.main-head')

<body class="gn-body">

<div class="gn-wrapper">

    <div class="gn-content">

        @include('donor.layouts.flash-box')

        @include('donor.registration.main-account-nav')

        <div class="form-page">
            @yield('content')
        </div>

    </div>

    @include('' . \App\Models\ClientInfo::clientViews() . 'layouts.main-footer', ['fullWidthFooter' => true])

</div>

@yield('footer-scripts')

<div class="scripts">
    <script src="/ma/plugins/jquery-ui/jquery-ui.min.js"></script>

    {{--<script src="/bootstrap/js/tether.min.js"></script>--}}
    <script src="/ma/plugins/popper.js"></script>
    <script src="/ma/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    {{--<script src="/bootstrap-material-design/js/bootstrap-material-design.min.js"></script>--}}
    <script src="/ma/plugins/jquery-confirm/dist/jquery-confirm.min.js"></script>
    {{--<script src="/ma/javascripts/handlebars.js"></script>--}}
    {{--<script src="/ma/javascripts/typeahead.bundle.min.js"></script>--}}

    <script src="/ma/plugins/daterangepicker/moment.min.js"></script>
    <script src="/ma/plugins/daterangepicker/daterangepicker.js"></script>
    {{--<script src="/ma/js/typeahead.bundle.min.js"></script>--}}
    {{--<script src="/ma/js/jquery.splendid.textchange.js"></script>--}}
    {{--<script src="/ma/js/jquery.textchange.min.js"></script>--}}

    {{--<script src="/javascripts/main.js"></script>--}}
    <script src="/ma/javascripts/daf_registration.js"></script>
    {{--<script src="/ma/javascripts/search.js"></script>--}}
    {{--<script src="/ma/javascripts/field-formats.js"></script>--}}
    {{--<script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>--}}

</div>

</body>

</html>
