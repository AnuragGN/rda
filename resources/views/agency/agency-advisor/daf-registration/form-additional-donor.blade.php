@php
$contact = new \App\Models\Contact();
$donorInfo = is_array($donorInfo ?? null) ? $donorInfo : (array)($donorInfo ?? []);
@endphp

@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFAdditionalDonor::title(), 'split84' => true])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">
                <div class="col-md-8">

                    @if(!\App\Models\ClientInfo::isHGA())
                        <p>You can add up to {{App\Models\ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR')}} additional account advisors.</p>
                    @endif

                    @if(\App\Models\ClientInfo::isPFR())
                        <p>
                            These are the individuals in addition to yourself that you give authority to have full DAF privileges (Full), just make DAF grants (Grant), or view the DAF only (View).
                        </p>
                    @endif

                    <form method="POST" action="{{ route('post-agency-daf-additional-donor', $id) }}" id="daf-account-info-form">
                        @csrf
                        <input type="hidden" name="key" value="{{ $donorInfo['key'] ?? '' }}">
                        <input type="hidden" name="isNew" id="id_isNew" value="{{ $donorInfo['isNew'] ?? '' }}">
                        <input type="hidden" name="contact_id" value="{{ $donorInfo['contact_id'] ?? '' }}">

                        <div class="form-group">
                            @include('errors.form-errors')
                        </div>

                        <div class="form-group">
                            <p class="form-title">Donor Advisor Information
                                @if (isset($donorInfo['isNew']))
                                    <a href="{{ route(\App\Models\ClientConfig::feature('DAF_REG_DAF_TYPE') ? 'daf-type' : $nextRedirectUrl, $id) }}" class="btn btn-sm btn-light col-sm-2" style="float: right">Skip</a>
                                @endif
                            </p>
                        </div>

                        @include('agency.agency-advisor.daf-registration.person', ['primary' => false, 'privileges' => true, 'model' => $donorInfo])

                        @include('agency.agency-advisor.daf-registration.address', ['model' => $donorInfo])

                        @if (in_array(App\Helpers\Data::DAFR_DONOR_INFO_MAILING_ADDRESS, $personFields))
                            @include('agency.agency-advisor.daf-registration.mailing-address', ['model' => $donorInfo])
                        @endif

                        <div class="form-btn-bar">
                            <div class="col-12 form-footer">
                                <div class="row">
                                    <p class="offset-md-3 col-md-3">
                                        <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
                                    </p>
                                    <p class="col-md-3">
                                        <button type="submit" name="save_next" value="1" class="btn btn-accent w100">SAVE & NEXT</button>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </form>

                    @if(!isset($donorInfo['isNew']))
                        <div class="col-md-12 text-right mb-1">
                            <a href="javascript:void(0);" id="id_delete_additional_donor" style="">Delete</a>

                            <form method="POST" action="{{ route('delete-agency-additional-donor', $id) }}"
                                  id="deleteAdditionalDonorForm" name="deleteAdditionalDonorForm">
                                @csrf
                                <input type="hidden" name="key" value="{{ $donorInfo['key'] ?? '' }}">
                                <input type="hidden" name="contact_id" value="{{ $donorInfo['contact_id'] ?? '' }}">
                            </form>
                        </div>
                    @endif

                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-additional-donor", "agency.agency-advisor."))

                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-additional-donor", "agency.agency-advisor."))
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#id_delete_additional_donor').on('click', function () {
            var body = $("body");
            var message = "<div style='text-align: center'>Are you sure you want to delete this Donor Advisor?</div><hr class='mb-0'>";
            $.confirm({
                columnClass: 'medium',
                title: '',
                content: message,
                buttons: {
                    no: { text: 'Cancel', btnClass: 'btn-light', keys: ['enter', 'shift'], action: function(){} },
                    yes: {
                        text: 'Delete', btnClass: 'btn-accent', keys: ['enter', 'shift'],
                        action: function(){
                            body.css("cursor", "progress");
                            body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                            document.deleteAdditionalDonorForm.submit();
                        }
                    }
                }
            });
            return false;
        });
    </script>

    <script>
        var full = "{{ \App\Helpers\Data::DAFR_DONOR_INFO_FUND_PRIVILEGE_FULL }}";

        $(function () {
            var privilege = $('#id_fund_privileges').val();
            if (privilege == full) {
                $('.ssn').prop('required', true).show(300);
                $(".date-of-birth").show(300);
                $("#id_dob").prop('required', true);
            } else {
                $(".ssn").hide().removeAttr('required').val('');
                $("#id_dob").removeAttr('required').val('');
                $(".date-of-birth").hide();
            }
        });

        $('#id_fund_privileges').on('change', function () {
            var valueSelected = this.value;
            if (valueSelected != full) {
                $(".ssn").hide(300).removeAttr('required').val('');
                $("#id_dob").removeAttr('required').val('');
                $(".date-of-birth").hide(300);
            } else {
                $('.ssn').prop('required', true).show(300);
                $(".date-of-birth").show(300);
                $("#id_dob").prop('required', true);
            }
        });
    </script>

@endsection
