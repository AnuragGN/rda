@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->MAKE_A_GRANT])

    @include('donor.grants._form_modal_search')

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-8">

                        <p class="fw300">Please provide the grant information in the form below.</p>

                        <div class="form-make-grant gn-form">
                            {{-- {!! Form::open( ['action' => 'FundController@saveGrant', 'files' => false, 'id' => 'content-form' ]) !!}--}}
                            {!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['add-to-cart'], 'id' => 'grant-form']) !!}
                            <div class="row">
                                <div id='id_change_form_layout' class="col-sm-11">
                                    @include('donor.grants._form')
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>

                    </div>
                    <div class="col-xl-4">
                        @include(\App\Models\ClientInfo::clientViewFor("grants.side-info-grants","donor."))
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
