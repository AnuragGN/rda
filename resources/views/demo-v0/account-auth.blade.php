@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                <p>You, as the Primary Account Holder may authorize an individual other than an Account Holder to receive duplicate statements, obtain information and or/perform transactions on your behalf. To facilitate this activity, please complete a donor advised fund account access form. This form can be found online at HighGroundAdvisorsDAF.org or by calling 1.800.xxx.xxxx.</p>

                <p class="registration-form-info">Please check below to consent.</p>

                <p class="form-title th-color">Authorization</p>

                <form id="registration-form">

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <textarea id="id-name" name="fname" rows="5" type="text" class="form-control" disabled="disabled" placeholder='Lorem ipsum'></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline form-check-authorize">
                            <input class="form-check-input" type="checkbox" id="same-as">
                            <label class="form-check-label" for="same-as">I Authorize</label>
                        </div>
                        <p id="auth-info-error" class="field-error">Please select checkbox.</p>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <div id="id-next-btn" class="col-9 form-footer">
                                <a href="/donor/successor" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-lg-4">
                @include('demo.side-pane')
            </div>

        </div>

    </div>


@endsection
