@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => $type->label . ' Address', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <div class="form-make-grant gn-form">
                            <form method="POST" action="{{ route('profile-address-save') }}" id="update-profile-address">
                                @csrf
                                @include('errors.form-errors')
                                @include('profiles._form_address')
                            </form>

                            <div style="text-align: right;" class="mt-4">
                                @if($isPrimary)
                                    <small style="color: #999">
                                        <i class="fas fa-info-circle"></i> Primary address cannot be deleted.
                                    </small>
                                @else
                                    @include('profiles._delete_address_link')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

