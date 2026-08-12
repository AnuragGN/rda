@extends ('admin.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => 'Filter'])
    <div class="container form-wrapper form-last" >
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{route('report-filter', ['type' => $type, 'new' => true])}}" >Go to Report filter</a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <h4 class="row page-title uppercase">Saved Report Filters</h4>
                    </div>

                    @foreach($filterList as $filter)
                        <div class="col-md-12">
                            <a href="{{route('report-config', ['type' => $type, 'id' => $filter->id])}}" >{{$filter->filter_name}}</a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection



