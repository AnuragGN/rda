@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => ""])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">
                        @include('charity.title-with-browser', ['title' => 'Organizations', 'link' => route('organizations-catalog')])
                        <p class="page-subtitle">Search results</p>
                        @include('charity.infinite-scroll-catalog', compact('query'))
                    </div>

                    <div class="col-lg-4">
                        @include('pane-placeholder')
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
