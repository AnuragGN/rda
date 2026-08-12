@php
$maxSecurities = \App\Models\ClientConfig::value('DAF_MAX_CONTRIBUTION_SECURITIES');
@endphp

@extends('donor.registration.main')

@section('content')

    <style>
        .contribution_type {
            padding: 1rem;
            border-radius: 50%;
            display: inline;
            font-weight: 600;
        }
    </style>

    @include('common.page-header', ['pageTitle' => 'Contributions'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-md-8">

                    <div class="form-group">
                        <p class="form-title">Securities or Mutual Funds</p>
                    </div>

                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    @if(\App\Models\ClientInfo::isHGA())
                        <p>
                            Upon approval of your application, HighGround will send you instructions so that you may
                            initiate the transfer of your securities or mutual funds from your financial institution.
                        </p>
                    @endif

                    <div>

                        @foreach($securities as $i => $security)

                            @if($security->isNew && count($securities) > 1)

                                @if(count($securities) <= $maxSecurities)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="javascript:void(0);" id="id_add_security_btn" onclick="addSecurity();">
                                                <i class="fas fa-plus-circle"></i> Add more
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div id="id_add_security" style="display: none" class="daf-form-card">

                                    <form method="POST"
                                          action="{{ route('post-daf-contributions-securities', $id) }}"
                                          class="daf-contribution-form"
                                          id="daf-contribution-{{ $security->key }}">
                                        @csrf

                                        <input type="hidden" name="key" value="{{ $security->key }}" id="id_security_key">
                                        <input type="hidden" name="isNew" value="{{ $security->isNew }}">

                                        @include("donor.registration._form_security")

                                    </form>
                                </div>

                            @else

                                <div id="id_security_{{ $security->key }}" class="daf-form-card">

                                    <form method="POST"
                                          action="{{ route('post-daf-contributions-securities', $id) }}"
                                          class="daf-contribution-form"
                                          id="daf-contribution-{{ $security->key }}">
                                        @csrf

                                        <input type="hidden" name="key" value="{{ $security->key }}" id="id_security_key">
                                        <input type="hidden" name="isNew" value="{{ $security->isNew }}">

                                        @include("donor.registration._form_security")

                                        @if(!$security->isNew)
                                            <div class="offset-md-9 col-md-3 text-right mb-1">
                                                <a href="javascript:void(0);"
                                                   class="delete-organization"
                                                   id="id_delete_orgs-{{ $security->key }}"
                                                   onclick="deleteSecurity(this)"
                                                   data="{{ $security->key }}">
                                                    Delete
                                                </a>
                                            </div>
                                        @endif

                                    </form>
                                </div>

                            @endif

                            <br>

                        @endforeach

                    </div>

                    @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-contributions", "donor."))

                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("registration.side-pane-securities", "donor."))
                </div>
            </div>

        </div>
    </div>

    <script>
        function addSecurity() {
            $('#id_add_security').show(600);
            $('#id_add_security_btn').fadeOut();
        }

        $(function(){
            var securities = getUrlParameter('tab');
            if (securities) {
                $('#id_contributions_tab a[href="#securities"]').tab('show');
            }
        });

        function deleteSecurity(e) {
            var key = $(e).attr('data');
            var body = $("body");

            var message = "<div style='text-align: center'>Are you sure you want to delete this item?</div><hr class='mb-0'>";

            $.confirm({
                columnClass: 'medium',
                title: '',
                content: message,
                buttons: {
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-light',
                        keys: ['enter', 'shift'],
                        action: function () {}
                    },
                    yes: {
                        text: 'Delete',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function () {
                            body.css("cursor", "progress");
                            body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                            window.location.href = "{{ route('delete-contributions-security', $id) }}?key=" + key;
                        }
                    }
                }
            });
            return false;
        }
    </script>

@endsection