@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Thank You'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            {{--start page-review--}}
            <div class="row mb-3">
                <div class="col-md-9">
                    @if(\App\Models\ClientInfo::isHGA())
                        Thank you for choosing HighGround Advisors to manage your donor-advised fund. Upon approval of your application, HighGround will send final documentation for electronic signature via email.
                    @else
                        <p>DAF application has been submitted. Admin will review the application and get back to you soon.</p>

                        <a href="javascript:void(0);"
                           data-message="Do you want to download the DAF application?"
                           data-href="{{route('agency-daf-application-download', $id)}}"
                           class="js_confirm_file_download btn btn-sm btn-accent"
                           title="PDF File">
                            Download Application <i class="fas fa-file-download"></i>
                        </a>

                    @endif

                </div>
            </div>

            <div class="row daf-review">
                @include('agency.agency-advisor.daf-registration.form-review-new')
            </div>
        </div>
    </div>
@endsection