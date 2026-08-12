@php
$passwordHelp = "Password must be at least 8 characters long and must have one uppercase character, one lowercase character, one special character and one number.";
@endphp

@extends ('donor.registration.main-account')

@section ('content')
    {{--@include('common.page-header', ['pageTitle' => 'Pool Performance'])--}}
    {{--@include('common.page-header', ['pageTitle' => "Donor-Advised Fund"])--}}
    @include('donor.registration.form-header')

    <div class="container custom-form pageTop">

        <div class="form-account-body">

            <div class="row">
                <div class="col-md-8">

                    <form method="POST" action="{{ route('post-daf-account') }}" id="daf-account-form">
                        @csrf

                    <div class="form-group">
                        <p class="form-title th-color">Create Donor-Advised Fund Login</p>

                        @if(\App\Models\ClientInfo::isHGA())
                            <p>Upon submission of this information, you will receive an email to access the donor-advised fund application.</p>
                        @else
                            <p>Please fill in the form below to create your DAF account.
                                After account creation, you can login to complete the Donor-Advised Fund application.</p>
                        @endif
                    </div>

                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    <div class="form-group row">
                        <label for="first_name" class="col-md-3 col-form-label text-right pr-0">Name</label>
                        <div class="col-md-3 mb-1">
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                   placeholder="first name" onkeypress="return /[a-z]/i.test(event.key)"
                                   value="{{ old('first_name', $model->first_name ?? '') }}" required>
                        </div>
                        <div class="col-md-3 mb-1">
                            <input type="text" name="last_name" class="form-control"
                                   placeholder="last name" onkeypress="return /[a-z]/i.test(event.key)"
                                   value="{{ old('last_name', $model->last_name ?? '') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
                        <div class="col-md-6">
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $model->email ?? '') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-form-label text-right pr-0">Password</label>
                        <div class="col-md-6 col-11">
                            <div class="input-group">
                                <input class="form-control" required name="password" type="password" id="password">

                                <div class="input-group-append">
                                    <div class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
                                        <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('common.tooltip-title-info', ['tooltipInfo' => $passwordHelp])

                        <div class="password-help" style="display: none"></div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-3 col-form-label text-right pr-0">Confirm Password</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input class="form-control" required name="password_confirmation" type="password" id="password_confirmation">

                                <div class="input-group-append">
                                    <div class="input-group-text" style="cursor: pointer;">
                                        <i class="fas fa-eye-slash" data-target-id="password_confirmation" style="width: 20px;" onclick="showPassword(this)"></i>
                                        <i class="fas fa-eye" data-target-id="password_confirmation" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-btn-bar">
                        <div class="col-md-12 form-footer">
                            <div class="row">
                                <p class="offset-md-3 col-md-3">
                                    <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">Submit</button>
                                </p>
                            </div>
                        </div>
                    </div>

                    </form>

                </div>

                <div class="col-md-4 client-info">
                    @include(\App\Models\ClientInfo::clientViewFor('registration.side-pane-register', 'donor.'))
                </div>
            </div>
        </div>

    </div>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });
        });

        function showPassword(item) {
            var targetId = $(item).data('target-id');
            var elem = $('#' + targetId);
            elem.parent().find(".fa-eye").show();
            elem.attr('type', 'text');
            $(item).hide();
        }
        function hidePassword(item) {
            var targetId = $(item).data('target-id');
            var elem = $('#' + targetId);
            elem.parent().find(".fa-eye-slash").show();
            elem.attr('type', 'password');
            $(item).hide();
        }
    </script>

@endsection
