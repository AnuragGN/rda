@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Confirm New Email'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="form-make-grant gn-form">
                            <form method="POST" action="{{ route('confirm-change-email') }}" id="confirm-change-email-form">
                                @csrf
                                @include('auth.email._form_confirm')
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

