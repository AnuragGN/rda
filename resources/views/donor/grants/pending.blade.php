@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Pending Disbursements / Grants'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">

                        <div class="row row-page-subtitle">
                            <div class="col-12">
                                <div class="page-subtitle">
                                    <h2>{{ $fund->name }}</h2>
                                </div>
                            </div>
                        </div>

                        @include('donor.grants.list-pending', ['showRepeat' => false])

                        <div class="row">
                            <div class="col-12 text-right">
                                <p>Total Amount = <span style="font-weight: 700;">${{$total}}</span></p>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-4 col-l-15">
                        @include('pane-placeholder', ['classTitle' => 'mt-2', 'class' => ''])
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
