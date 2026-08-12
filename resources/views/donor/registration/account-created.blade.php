
@extends ('donor.registration.main-account')

@section ('content')

    {{--@include('donor.registration.form-header')--}}

    <div class="container custom-form pageTop mt-5">
        <div class="form-body">

            @if (\App\Models\ClientInfo::isHGA())
                <div class="form-wrapper form-last">
                    <div class="row">
                        <div class="col-lg-10">
                            <br>
                            <h3>YOU’RE ALMOST THERE</h3>
                            <p>An email confirmation has been sent to {{$email}}. Please click the link in the
                                confirmation email to access the donor-advised fund application.</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="/" class="btn btn-theme w100" style="width: 120px">Home</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="form-wrapper form-last">
                    <div class="row">
                        <div class="col-lg-10">
                            <br>
                            <h3 style="color: #000">Your DAF Account has been created.</h3>
                            <br>
                            <h5 style="line-height: 1.5">We have sent you an email on {{$email}}. Please read the email and follow instructions to confirm your email address and to complete your DAF application.</h5>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="/" class="btn btn-theme w100" style="width: 120px">Home</a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

