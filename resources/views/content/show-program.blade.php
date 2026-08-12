@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Program'])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">

                <div class="col-lg-8 col-r-15 content-page">
                    <h2 class="page-subtitle uppercase">{{$model->title}}</h2>
                    {!! $model->content_text !!}
                </div>

                <div class="col-lg-4 col-l-15">
                    {{--@include('pane-placeholder', ['class' => ''])--}}
                </div>

            </div>
        </div>
    </div>

@endsection
