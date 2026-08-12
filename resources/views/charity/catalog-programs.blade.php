@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => ""])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">


                        @include('charity.title-with-browser', ['title' => 'Programs Catalog'])

                        <div class="row">
                            <div class="col-12">
                                <ul class="program-catalog">
                                    @foreach($items as $key => $item)
                                        <a href="{{route('programs-by-interest-area', ['interest_area_id' => $item['id']])}}">
                                            <li>{{$item['name']}} - {{$item['count']}}</li>
                                            <span><i class="fas fa-angle-right"></i></span>
                                        </a>

                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        @include('pane-placeholder')
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
