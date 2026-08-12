@php
$successors = json_decode($fullDAFInfo['successors'] ?? '{}');
$givingTotal = \App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
@endphp

@extends('donor.registration.main')

@section('content')

<div id="id_review">

    @include('common.page-header', ['pageTitle' => 'Review Application'])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row daf-review">
                @include('donor.registration.form-review')
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="form-btn-bar text-left">
                        <div class="col-md-12 form-footer">

                            @if ($givingTotal == 100 || optional($successors->endowment)->isSelected == true)
                                <p class="col-md-3 pl-0 mt-3">
                                    <button type="button"
                                            class="btn btn-accent w100"
                                            id="id_continue_btn"
                                            onclick="showAuthorization()">
                                        Continue
                                    </button>
                                </p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="id_authorization_form" style="display: none;">
    @include(\App\Models\ClientInfo::clientViewFor('registration._form-authorization-submit', 'donor.'))
</div>

<script>
    $(function(){
        var affirmationItem = $('#id_authorization');

        if (affirmationItem.length) {
            $(':input[type="submit"]').prop('disabled', true);
        }

        affirmationItem.change(function() {
            $(':input[type="submit"]').prop('disabled', this.checked);
        });
    });

    function showAuthorization() {
        $('#id_review').hide();
        $('#id_authorization_form').show();
        $(window).scrollTop(0);
    }
</script>

@endsection