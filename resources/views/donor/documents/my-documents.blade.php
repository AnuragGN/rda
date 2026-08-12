
@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->FUND_DOCUMENTS])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">

                <div class="col-lg-8">
                    @include('donor.documents.my-documents-menu')
                </div>

                <div class="col-lg-4">
                    @if(!\App\Models\ClientInfo::isHGA())
                        @include('pane-placeholder')
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection

