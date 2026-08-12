@extends (\App\Helpers\GnUtils::getUserView('layouts.main'))

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->FUND_OVERVIEW])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">

                    <div class="col-lg-8 col-r-15">

                        @include(\App\Models\ClientInfo::clientViewFor('statements.overview-filters', 'donor.'))

                        <div class="row row-page-title">
                            <div class="col-12">
                                <h2 class="page-title">
                                    <span>{{ $custom->text->FUND_OVERVIEW }}</span>
                                </h2>
                                <br>
                                @if($date)
                                    <p><i class="fas fa-exclamation-triangle"></i> Fund statement of the selected date doesn't exist.</p>
                                @else
                                    @if(\App\Models\ClientInfo::isMercy())
                                        <p><i class="fas fa-exclamation-triangle"></i> Fund statement doesn't exist. Fund statement will be available based upon fund data and fund design choosen.</p>
                                    @else
                                        <p><i class="fas fa-exclamation-triangle"></i> Fund statement doesn't exist.</p>
                                    @endif
                                @endif

                                <br>
                                <div style="text-align: right">
                                    <a href="{{ url()->previous() }}" class="cancel" onclick="">Go back</a>
                                </div>
                            </div>

                        </div>

                        <hr>

                    </div>

                    <div class="col-lg-4 col-l-15">
                        @include('pane-placeholder')
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
