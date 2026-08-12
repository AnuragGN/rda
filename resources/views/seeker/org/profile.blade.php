    @extends ('seeker.layouts.main')

@section ('content')

    <section class="content-header">
        <div class="container">
            <div class="row mt-2">
                <div class="col-sm-6">
                    <h1>Organization Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Text Editors</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('org.organization')
                </div>
            </div>
        </div>
    </section>

@endsection
