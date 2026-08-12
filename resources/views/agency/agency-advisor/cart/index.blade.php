@extends ('agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Pending Recommendation'])

<section class="content">
    <div class="container">
        <div class="form-wrapper form-last">   
            <div class="row">
                <div class="col-xl-12">
                    <h1 class="page-title two-column w100 mt-2">
                        <span></span>
                        <a class=" hide">
                            <select class="form-control" id="fund_id" name="fund_id" onchange="getfund();">
                                <option value="0">All Fund</option>
                                 @foreach($funds as $fund =>$val)
                                    <option value="{{ $fund }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </a>
                    </h1>
                    @include('agency.agency-advisor.cart.list-loader')
                </div>
            </div>
        </div>
    </div>
</section>
@include('agency.agency-advisor.cart.advisor_js')

@endsection
