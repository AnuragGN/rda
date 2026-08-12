
{{-- Footer Scripts --}}

<div class="scripts">
    {{--<script src="/ma/bootstrap/js/tether.min.js"></script>--}}
    {{--<script src="/ma/plugins/popper.js"></script>--}}
    {{-- jquery-ui.js, if required, must come before bootstrap.bundle.min.js for tooltip to work--}}
    <script src="/ma/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="/ma/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="/ma/plugins/handlebars.js"></script>
    <script src="/ma/plugins/typeahead.bundle.min.js"></script>

    {{--<script src="/ma/js/jquery.splendid.textchange.js"></script>--}}
    {{--<script src="/ma/js/jquery.textchange.min.js"></script>--}}

    {{--<script src="/ma/plugins/jquery-ui/jquery-ui.min.js"></script>--}}
    <script src="/ma/plugins/jquery-confirm/dist/jquery-confirm.min.js"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <script src="/ma/plugins/chart.js/Chart.min.js"></script>
    <script src="/ma/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/ma/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>

    <script src="/ma/plugins/daterangepicker/moment.min.js"></script>
    <script src="/ma/plugins/daterangepicker/daterangepicker.js"></script>

    <script src="/ma/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="/ma/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <script src="/ma/adminlte/js/adminlte.js"></script>
    {{--<script src="/ma/adminLTE/js/pages/dashboard.js"></script>--}}
    {{--<script src="/ma/adminLTE/js/demo.js"></script>--}}


    <script src="/ma/javascripts/main.js"></script>
    <script src="/ma/javascripts/search.js"></script>

    @if(\App\Models\ClientConfig::feature('GUIDE_STAR_CANDID'))
        <script type="text/javascript" src="/ma/javascripts/search-grant-org-candid.js" charset="utf-8"></script>
    @else
        <script type="text/javascript" src="/ma/javascripts/search-grant-org.js" charset="utf-8"></script>
    @endif

    <script src="/ma/javascripts/field-formats.js"></script>
    <script src="/ma/javascripts/pie-chart.js"></script>
    <script src="/ma/javascripts/bar-chart.js"></script>
    <script src="/ma/javascripts/line-chart.js"></script>
    <script src="/ma/javascripts/pie-chart-ds.js"></script>

    <script src="/ma/agency/js/main.js"></script>
    <script src="/ma/agency/js/search.js"></script>
</div>