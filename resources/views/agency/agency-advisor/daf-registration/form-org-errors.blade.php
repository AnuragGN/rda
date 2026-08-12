@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFSuccessors::title() . ' - ' . old('org_name')])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">

                <div class="col-md-9">
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    <div id="id_orgs" class="daf-form-card" >
                        <form method="POST" action="{{ route('post-agency-daf-successors-organizations', $id) }}"
                              id="daf-successors-individuals">
                            @csrf
                            <input type="hidden" name="key" value="{{ old('key') }}">
                            <input type="hidden" name="isNew" value="{{ old('isNew') }}">

                            @include('agency.agency-advisor.daf-registration._form_organizations')
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
