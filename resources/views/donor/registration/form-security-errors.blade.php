@extends ('donor.registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Contributions'.' - '.old('fund_name')])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">

                <div class="col-md-8">
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    <div id="id_orgs" class="daf-form-card" >
                        <form method="POST" action="{{ route('post-daf-contributions-securities', $id) }}"
                              class="daf-contribution-form" id="daf-contribution">
                            @csrf
                            <input type="hidden" name="key" value="{{ old('key') }}">
                            <input type="hidden" name="isNew" value="{{ old('isNew') }}">
                            @include('donor.registration._form_security')
                        </form>
                    </div>

                </div>
                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("registration.side-pane-securities", "donor."))
                </div>
            </div>

        </div>
    </div>

@endsection
