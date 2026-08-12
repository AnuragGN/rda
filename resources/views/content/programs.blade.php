@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'CCT Initiatives'])

    <style>
        .custom-programs {
            display: inline-block;
            /*margin-left: -1rem;*/
        }
        .custom-programs .title {
            font-size: 1rem;
            font-weight: 600;
            /*margin-left: 1rem;*/
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">

                <div class="col-lg-8 col-r-15">
                    <h3 class="page-subtitle uppercase mt-2">Our Programs</h3>
                    @foreach($models as $model)
                        <div class="custom-programs">
                            <a  class="title" href="{{route('content.programs.show', $model->content_id)}}">{{ $model->title }}</a>
                            <a href="{{route('content.programs.show', $model->content_id)}}">
                                {!! $model->abstract !!}
                            </a>
                        </div>
                        <hr>
                    @endforeach
                </div>

                <div class="col-lg-4 col-l-15">
                    {{--@include('pane-placeholder', ['classTitle' => 'mt-2'])--}}
                </div>

            </div>
        </div>
    </div>

@endsection
