
@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $name])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">

                <div class="col-lg-8">

                    @forelse($data as $doc)
                        <p>
                            Uploaded on {{\App\Helpers\GnUtils::customDate($doc->created_at)}} (File {{$doc->userFilenameOnly()}})
                            <br> Download
                            <a href="{{route('download-documents', $doc->key)}}" style="font-style: italic">
                                 {{$doc->savedFilename()}} <i class="fas fa-file-download"></i>
                             </a>
                         </p>
                    @empty
                        <p>No data found</p>
                    @endforelse

                </div>

                <div class="col-lg-4">
                    @include('donor.documents.my-documents-menu', ['rPane' => true])
                    {{--@include('pane-placeholder')--}}
                </div>

            </div>
        </div>
    </div>

@endsection

