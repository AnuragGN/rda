@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Dashboard', 'hcXlWidth' => '10'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">

                <div class="row">
                    <div class="col-xl-8 col-r-15">

                        <h3 class="page-subtitle uppercase mt-2">Funds</h3>
                        @include('donor.funds.list')
                        <br>
                        <br>
                        <br>

                        @if(\App\Models\ClientInfo::isGNA() && \App\Helpers\GnUtils::isDonorSession())
                            <div class="d-none d-lg-block">
                                @include('donor.funds.home-filler')
                            </div>
                        @endif

                    </div>

                    @if(true or \App\Helpers\GnUtils::isDonorSession())
                        <div class="col-xl-4 col-l-15">
                            @include('pane-placeholder', ['classTitle' => 'mt-2', 'class' => ''])
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

@endsection
