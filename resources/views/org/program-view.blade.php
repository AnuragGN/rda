@extends ('donor.layouts.main')

@section ('content')
    <style>
        .logo img { background: #f0f0f0; }
    </style>

    @include('common.page-header', ['pageTitle' => 'Program'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15 org-view">

                        <div class="page-subtitle">
                            <h2>{{$program->title}}</h2>
                            <a target="_blank" href="{{ route('print-program', $program->org_need_app_id) }}"
                               class="btn btn-sm btn-light"
                               data-toggle="tooltip" title="Print Program Info">
                                Print <i class="fas fa-print"></i>
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-12 image-header">
                                <img src="{{$program->image}}" />
                                <div class="mag-link">
                                    <a href="{{ $program->getMakeGrantUrl() }}" class="btn btn-theme btn-sm">{{ $custom->text->MAKE_A_GRANT }}</a>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="font-small grey600">A Project of
                                    <a href="{{$program->organization->getUrl()}}" class="fw600">{!! $program->organization->name !!}</a>
                                </span>
                            </div>

                            <div class="col-12">
                                <span class="font-small grey600 fw400">Amount needed:</span>
                                <span class="fw600 text-accent">{{ \App\Helpers\GnUtils::money($program->total_requested) }}</span>
                            </div>
                        </div>

                        <h4 class="page-subtitle">Summary</h4>
                        <div>{!! $program->summary !!}</div>

                        <br/>

                    </div>

                    <div class="col-lg-4 col-l-15">
                        @include('pane-placeholder')
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
