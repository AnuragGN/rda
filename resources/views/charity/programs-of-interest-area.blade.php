@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => ""])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">
                        @include('charity.title-with-browser', ['title' => 'Programs', 'link' => route('programs-catalog')])
                        <h4 class="page-subtitle">{{$title}}</h4>
                        @include('charity.program-list-items')
                    </div>

                    <div class="col-lg-4 col-l-15">
                        @include('pane-placeholder')
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
