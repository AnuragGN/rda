@extends('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\ClientConfig::text('INVESTMENTS')])

    <div class="container investment">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-8 col-r-15">

                    @include(\App\Models\ClientInfo::clientViewFor('investments.info-view', 'donor.'))

                    @if(count($selector) > 1)
                        <div class="row">
                            <div class="col-md-5">
                                {!! Form::select('fund_id', $selector, $id, ['class' => 'form-control ', 'id' => 'id_fund_selector']) !!}
                                <br>
                            </div>
                        </div>
                    @endif

                    <h5 class="page-subtitle mt-2" style="justify-content: normal;">
                        <div class="col-md-6">{{\App\Models\Investments::poolTitle()}}</div>
                        <div class="col-md-2">Current Allocation %</div>
                        @if($requested)
                            <div class="col-md-2">Requested Allocation %</div>
                        @endif
                    </h5>

                    @foreach($allocations as $allocation)
                        <div class="row fund-allocation">

                            @if($allocation->pool_link)
                                <div class="col-md-6">
                                    <a href="{{$allocation->pool_link}}" target="_blank">
                                        {{$allocation->pool_name}}
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            @else
                                <div class="col-md-6">{{$allocation->pool_name}}</div>
                            @endif

                            <div class="col-md-2">{{$allocation->allocation}}</div>
                            @if($requested)
                                <div class="col-md-2">{{$allocation->requested_allocation}}</div>
                            @endif
                        </div>
                    @endforeach

                    <br>
                    <a href="{{route('edit-investments', $id)}}" class="btn btn-accent">
                        {{\App\Models\ClientInfo::isHGA() ? "Change Selections" : "Change Fund Allocation"}}
                    </a>

                    <br>
                    <br>
                    <hr>
                </div>
            </div>

            @include(\App\Models\ClientInfo::clientViewFor('investments.info-footnote', 'donor.'))

        </div>
    </div>

    <script>
        $('#id_fund_selector').change(function(){
            var data= $(this).val();
            window.location.href = '/m/donor/investments/' + data;
        });
    </script>

@endsection
