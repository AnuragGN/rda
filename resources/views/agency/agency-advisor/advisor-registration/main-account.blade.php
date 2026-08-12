@php $dafRegistrationPage = true; @endphp
<!DOCTYPE html>
<html>

@include('agency.layouts.main-head')

<body class="gn-body {{ \App\Models\FaPartner::getBrandBodyClasses() }}">

<div class="gn-wrapper">

    <div class="gn-content">

        @include('agency.layouts.flash-box')

        @include('agency.agency-advisor.advisor-registration.main-account-nav')

        <div class="form-page">
            @yield('content')
        </div>

    </div>

    @include('agency.layouts.main-footer', ['fullWidthFooter' => true])

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
