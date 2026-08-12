@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => $type->label . ' Phone Number', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <div class="form-make-grant gn-form">
                            <form method="POST" action="{{ route('profile-phone-save') }}" id="update-profile-phone">
                                @csrf
                                @include('errors.form-errors')
                                @include('profiles._form_phone')
                            </form>

                            <hr class="mt-5 mb-2">

                            <div style="text-align: right;">
                                @if($isPrimary)
                                    <small style="color: #999"><i class="fas fa-info-circle"></i> Primary phone cannot be deleted.</small>
                                @else
                                    @include('profiles._delete_phone_link')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

