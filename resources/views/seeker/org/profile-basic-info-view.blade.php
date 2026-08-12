@extends ('seeker.layouts.main')

@section ('content')

    @include('seeker.common.page-header', ['pageTitle' => 'Organization'])

    <section class="content">
        <div class="container">
            <div class="row org-profile-view">
                <div class="col-12 offset-sm-3 offset-md-0 col-sm-9 col-lg-3 col-xl-3 order-2 order-lg-2 order-xl-2 col-lr-15">
                    <div class="card text-center bg-secondary text-white">
                        <div class="card-body">
                            <p class="card-text text-uppercase mb-1">Upload Logo</p>
                            <small class="card-text">(Prefered width=150px)</small>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1">

                            <p class="card-text text-uppercase mb-1 mt-3">Upload Image</p>
                            <small class="card-text">(Prefered width=600px, height=400px)</small>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1">

                        </div>
                    </div>
                </div>


                <div class="col-12 col-lg-9 col-xl-8 order-1 order-lg-1 order-xl-1 col-lr-15">
                    <div class="card gn-shadow">
                        <div class="header text-uppercase">
                            <div>Organization Info</div>
                        </div>

                        <div class="body">
                            <div class="mb-2" style="display: flex; justify-content: space-between;">
                                    <span>
                                        <div>{{$org->name}}</div>
                                        <div><label class="mb-0">Email</label> {{$org->preferred_email}}</div>
                                        @if($org->web_site)
                                            <div><label class="mb-0">Website</label> {{$org->web_site}}</div>
                                        @endif
                                        <div><label class="mb-0">Annual Operating Revenue</label> {{ \App\Helpers\GnUtils::money($org->annual_operating_revenue) }}</div>

                                    </span>
                                <a href="{{route('gs-org-profile-edit')}}" class="txt-btn-sm">EDIT</a>
                            </div>
                            @include('seeker.org.view_phones')
                        </div>


                    </div>

                    @include('seeker.org.view_addresses')

                </div>
            </div>
        </div>
    </section>

@endsection
