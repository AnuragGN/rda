@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Edit Profile', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <div class="form-make-grant gn-form">
                            <form method="POST" action="{{ route('profile-save') }}" id="update-profile">
                                @csrf
                                @include('errors.form-errors')
                                @include('profiles._form_profile')
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

