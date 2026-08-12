@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                <p>Any grants made to approved charities will have a letter that includes the Donor Advised Fund account name, unless a specific request is made for the grant to remain anonymous.</p>

                {{--<p class="registration-form-info">Please fill the Giving Account Name/Fund Name below.</p>--}}

                <p class="form-title th-color">Giving Account Name</p>


                <div id="id-form">

                    <form id="registration-form">

                        <div class="form-group row">
                            <label for="id-name" class="col-sm-3 col-form-label text-right">Giving Account Name</label>
                            <div class="col-sm-6">
                                <input id="id-name" name="fname" type="text" class="form-control" placeholder='e.g. "The Smith Family Fund"' required="">
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div id="id-next-btn" class="col-9 form-footer">
                                    <a href="/donor/account-auth" class="btn btn-hga-md btn-wide btn-theme">Save & Next</a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </div>

            <div class="col-lg-4">
                @include('demo.side-pane')
            </div>

        </div>

    </div>


@endsection
