@extends ('donor.layouts.main', ['container' => 'container transaction-container'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Organization Matches'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">
                        @include('donor.common.infinite-scroll')
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            jsInfiniteScroll.init('/m/catalog/orgs/matches/ajax');
            jsInfiniteScroll.runLoadData();
        });
    </script>

@endsection
