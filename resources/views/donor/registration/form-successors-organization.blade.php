@php
$states = \App\Models\State::getCodeListUSA();
$countries = \App\Models\Country::getListUSAOnly();
$phoneTypes = \App\Models\PhoneType::selectContactPhoneTypes();
$prefixes = \App\Models\Prefix::getSelectable();
$maxOrgs = \App\Models\ClientConfig::value('DAF_MAX_SUCCESSORS_ORGANIZATIONS');
$givingTotal = App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
@endphp
@extends ('donor.registration.main')

@section ('content')
    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFSuccessors::title()])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">

                <div class="col-md-8">
                    <div class="form-group">
                        <p class="form-title">Charitable Organizations</p>
                    </div>

                    @include('donor.registration._form-successors_allocation', compact($successorDesignation = false))

                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>


                    @foreach($organizations as $i => $organization)

                        @if($organization->isNew && count($organizations) > 1)
                            @if ( count($organizations)-1 < $maxOrgs )
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0);" id="id_add_organization_btn" onclick="addOrganization();">
                                            <i class="fas fa-plus-circle"></i> Add more</a>
                                    </div>
                                </div>
                            @endif
                            <div id="id_add_organization" style="display: none" class="daf-form-card" >
                                <form method="POST" action="{{ route('post-daf-successors-organizations', $id) }}"
                                      id="daf-successors-individuals-{{ $organization->key }}">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $organization->key }}">
                                    <input type="hidden" name="isNew" value="{{ $organization->isNew }}">

                                    @include('donor.registration._form_organizations')
                                </form>
                            </div>
                        @else
                            <div id="{{'id_organization_' . $organization->key}}" class="daf-form-card" >
                                <form method="POST" action="{{ route('post-daf-successors-organizations', $id) }}"
                                      id="daf-successors-individuals-{{ $organization->key }}">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $organization->key }}">
                                    <input type="hidden" name="isNew" value="{{ $organization->isNew }}">

                                    @include("donor.registration._form_organizations", compact('organization'))
                                    @if(!$organization->isNew)
                                        <div class="offset-md-9 col-md-3 text-right mb-1">
                                            <a href="javascript:void(0);" class="delete-organization" id="id_delete_orgs-{{$organization->key}}" onclick="deleteOrg(this)" data="{{$organization->key}}" style="">Delete</a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endif

                        <br>
                    @endforeach

                    @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-successors", "donor."))

                </div>
            </div>
        </div>
    </div>
    <script>
        function addOrganization() {
            $('#id_add_organization').show(600);
            $('#id_add_organization_btn').fadeOut();
        }

        var total = '{{$givingTotal}}';
        if (total > 100) {
            $('#total_warning').html("<i class='fa fa-exclamation-triangle'></i> Total percentage greater than 100%.");

            $('#id_add_organization_btn').hide();
            //$('#id_successor_menu').html("<i class='fa fa-exclamation-triangle' style='color: #f1f1f1;'></i>");
        }

        function deleteOrg(e) {

            var key = $(e).attr('data');
            var body = $("body");

            var message = "<div style='text-align: center'>Are you sure you want to delete this organization?</div><hr class='mb-0'>";

            $.confirm({
                columnClass: 'medium',
                title: '',
                content: message,
                buttons: {
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-light',
                        keys: ['enter', 'shift'],
                        action: function () {
                        }
                    },
                    yes: {
                        text: 'Delete',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function () {
                            body.css("cursor", "progress");
                            body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                            window.location.href = "{{route("delete-successors-organization", $id)}}?key="+key;

                        }
                    }
                }
            });
            return false;
        };
    </script>

@endsection
