
@extends ('donor.layouts.main')

@section ('content')

    <style>
        .file_upload {
            width: 100%;
            background: #fafafa;
            border: 1px solid #f0f0f0;
        }
    </style>
    @include('common.page-header', ['pageTitle' => 'Upload ' . $name])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">

                <div class="col-lg-8">

                    @include('errors.form-errors')

                    <p> Choose file and click on Upload button to upload the file.</p>
                    <form action="{{ route('document-upload-post', $type) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <input type="file" name="file" class="file_upload">
                            </div>

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-accent btn-sm">Upload</button>
                            </div>

                        </div>
                    </form>

                </div>

                <div class="col-lg-4">
                    @include('donor.documents.my-documents-menu', ['rPane' => true])
                    {{--@include('pane-placeholder')--}}
                </div>

            </div>
        </div>
    </div>

@endsection
