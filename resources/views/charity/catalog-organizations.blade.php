@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => ""])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8">

                        @include('charity.title-with-browser', ['title' => 'Organizations Catalog'])

                        <ul class="org-catalog">

                            @foreach($items as $key => $value)
                                <a href="javascript:void(0)" class="category" data-child-id="id_{{$value['id']}}" onclick="sageCollapsible(this)">
                                    <li class="">{{$key}} - {{$value['total']}}</li>
                                    <span><i class="fas fa-angle-right"></i><i class="fas fa-angle-down" style="display: none"></i></span>
                                </a>

                                <ul id="id_{{$value['id']}}" style="display: none">
                                    @foreach($value['items'] as $child)
                                        <a href="{{route('orgs-by-interest-area', ['interest_area_id' => $child['id']])}}">
                                            <li>{{$child['name']}} - {{$child['count']}}</li>
                                            <span><i class="fas fa-angle-right"></i></span>
                                        </a>
                                    @endforeach
                                </ul>
                            @endforeach

                        </ul>
                    </div>

                    <div class="col-lg-4">
                        @include('pane-placeholder')
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
