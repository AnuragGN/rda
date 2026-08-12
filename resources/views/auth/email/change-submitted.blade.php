@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Request submitted'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">

                        <p>Your request to change your email address to <b>{{$email}}</b> has been saved.</p>
                        <p>We have sent you an email. Please read the email and follow instructions to confirm your email address.</p>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

