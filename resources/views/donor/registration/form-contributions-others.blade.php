@php
$maxSecurities = \App\Models\ClientConfig::value('DAF_MAX_CONTRIBUTION_SECURITIES');
@endphp

@extends('donor.registration.main')

@section('content')

    @include('common.page-header', ['pageTitle' => 'Contributions'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-md-9">

                    <div class="form-group">
                        <p class="form-title">Other Contributions</p>
                    </div>

                    <form method="POST"
                          action="{{ route('post-daf-contributions-others', $id) }}"
                          id="contributions_others_form">

                        @csrf

                        <div class="daf-form-card">

                            <div class="form-group row">

                                <input type="checkbox"
                                       name="is_active"
                                       id="is-active"
                                       class="col-1 form-control2 checkbox-1x"
                                       value="1"
                                       {{ old('is_active', $others->is_active ?? false) ? 'checked' : '' }}>

                                <div class="col-11">
                                    If you would like to make a contribution with non-cash assets, check here and a HighGround representative will reach out to you.
                                </div>

                            </div>

                            <div class="form-btn-bar">
                                <div class="col-md-12 form-footer">
                                    <div class="row">

                                        <p class="offset-md-3 col-md-3">
                                            <button type="submit"
                                                    name="save"
                                                    id="id_save_btn"
                                                    class="btn btn-wide btn-accent w100">
                                                SAVE
                                            </button>
                                        </p>

                                        <p class="col-md-3">
                                            <button type="submit"
                                                    name="save_next"
                                                    class="btn btn-accent w100">
                                                SAVE & NEXT
                                            </button>
                                        </p>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>

                    @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-contributions", "donor."))

                </div>
            </div>
        </div>
    </div>

@endsection