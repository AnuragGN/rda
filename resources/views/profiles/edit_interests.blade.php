@extends ('donor.layouts.main', ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Interest Profile'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <p class="fw300">Please select your interests from the list below.
                            This information will help us connect you with <b>Organizations</b> and <b>Projects</b> that match your interests.
                            Please feel free to check and uncheck any categories to fine-tune your interest profile.</p>

                        <div class="row">
                            <div class="col-12">

                                <form method="POST" action="{{ route('profile-interests-save') }}" id="update-profile-interests">
                                    @csrf
                                    @include('errors.form-errors')
                                    @include('profiles._form_interests')
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
