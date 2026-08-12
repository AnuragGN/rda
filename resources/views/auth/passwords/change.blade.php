@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Change Password'])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="form-make-grant gn-form">
                            <form id="change-password-form" method="POST" action="{{ route('change-password') }}">
                                @csrf
                                @include('auth.passwords._form_change')
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function(){
            setActiveTab("ul.id-profile-menu", "#id-default-profile-menu");
        });
    </script>
@endsection

