@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFSuccessors::title() . ' - ' . old('first_name') . ' ' . old('last_name')])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">

                <div class="col-md-9">
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    <div id="id_individual" class="daf-form-card" >
                        <form method="POST" action="{{ route('post-agency-daf-successors-individuals', $id) }}"
                              id="daf-successors-individuals">
                            @csrf
                            <input type="hidden" name="key" value="{{ old('key') }}">
                            <input type="hidden" name="isNew" value="{{ old('isNew') }}">
                            <input type="hidden" name="contact_id" value="{{ old('contact_id') }}">

                            @include('agency.agency-advisor.daf-registration.person')

                            <div class="form-group row">
                                <label for="id_share_value" class="col-md-3 col-form-label form-multi-line-label text-right pr-0">% of Giving Account</label>
                                <div class="col-md-3">
                                    <input type="number" name="share_value" id="id_share_value"
                                           class="form-control" onchange="handleChange(this);"
                                           value="{{ old('share_value') }}" required>
                                </div>
                            </div>

                            @include('agency.agency-advisor.daf-registration.address')

                            <div class="form-btn-bar">
                                <div class="col-md-12 form-footer">
                                    <div class="row">
                                        <p class="offset-md-3 col-md-3">
                                            <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
                                        </p>
                                        <p class="col-md-3">
                                            <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function handleChange(input) {
            if (input.value < 1) {
                input.value = 1;
                alert('Minimum 1 allowed');
            }
            if (input.value > 100){
                input.value = 100;
                alert('Maximum 100 allowed');
            }
        }
    </script>
@endsection
