
@extends ('agency.agency-advisor.daf-registration.open-account.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => 'DAF Sponsor-'. $sponsor->name, 'split84' => true])
    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">
                <div class="col-md-8">
                    <form method="POST" action="{{ route('post-agency-daf-account') }}" id="daf-account-form">
                        @csrf
                        <input type="hidden" name="sponsor_id" id="sponsor_id" value="{{ $sponsor->id  }}" >
                        <div class="form-group">
                            @include('errors.form-errors')
                        </div>
                        <div class="form-group">
                            <p class="form-title">Open Donor Advised fund</p>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right pr-0">Name</label>
                            <div class="col-md-3 mb-1">
                                <input type="text" name="first_name" class="form-control" placeholder="first name"  required onkeypress="return /[a-z]/i.test(event.key)"
                                >
                            </div>
                            <div class="col-md-3 mb-1">
                                <input type="text" name="last_name" class="form-control" placeholder="last name" required onkeypress="return /[a-z]/i.test(event.key)"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
                            <div class="col-md-6">
                                <input type="email" name="email" id="email" class="form-control" required value="{{ old('email', $model->email ?? '') }}"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-right pr-0">Password</label>
                            <div class="col-md-6 col-11">
                                <div class="input-group">
                                    <input class="form-control" required="required" name="password" type="password" id="password">

                                    <div class="input-group-append">
                                        <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
                                            <i class="fas fa-eye-slash" data-target-id="password" style="width: 20px;" onclick="showPassword(this)"></i>
                                            <i class="fas fa-eye" data-target-id="password" style="width: 20px; display: none" onclick="hidePassword(this)"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div style="max-width: 28px!important; padding: 0 6px;"
                                data-toggle="tooltip"
                                data-html="true"
                                title="Password must be at least 8 characters long and must have one uppercase character, one lowercase character, one special character and one number.">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="password-help" style="display: none"></div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-right pr-0">Confirm Password</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    {{--{!! Form::text('password_confirmation', null, ['class' => 'form-control', 'rows' => 2, 'required']) !!}--}}
                                    <input class="form-control" required="required" name="password_confirmation" type="password" id="password_confirmation">

                                    <div class="input-group-append">
                                        <div class="input-group-text" id="btnGroupAddon" style="cursor: pointer;">
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
                                        <button
                                            type="submit"
                                            name="save"
                                            id="id_save_btn"
                                            class="btn btn-accent w100">
                                            SAVE
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-account-info", "agency.agency-advisor."))
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
