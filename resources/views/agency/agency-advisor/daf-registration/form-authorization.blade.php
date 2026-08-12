<?php
//$donor = json_decode($fullDAFInfo['donor']);
//$additionalDonors = json_decode($fullDAFInfo['donors']);
//$contributions = json_decode($fullDAFInfo['contributions']);
//$investments =  \App\Models\DAFAccount::getCurrentAllocation($id);

$successors = json_decode($fullDAFInfo['successors']);
$givingTotal = \App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
?>
@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    <div id="id_review">

        @include('common.page-header', ['pageTitle' => 'Review Application'])

        <div class="container pageTop">
            <div class="form-body form-wrapper form-last custom-form">

                <div class="row daf-review">
				{{-- @include('agency.agency-advisor.daf-registration.form-review') --}}
                    @include('agency.agency-advisor.daf-registration.form-review-new')
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-btn-bar text-left">
                            <div class="col-md-12 form-footer">

                                    <p class="col-md-3 pl-0 mt-3">
                                        <a href="javascript:void(0)" class="btn btn-accent w100" id="id_continue_btn" onclick="showAuthorization()"> Continue </a>
                                    </p>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{--end page-review--}}

        </div>
    </div>
        
    {{--page-authorization--}}
    <div id="id_authorization_form" style="display: none;">
        @include(\App\Models\ClientInfo::clientViewFor('daf-registration._form-authorization-submit', 'agency.agency-advisor.'))
    </div>


    <script>
        $(function(){
            var affirmationItem = $('#id_authorization');
            if ( affirmationItem.length ) {
                $(':input[type="submit"]').prop('disabled', true);
            }
            affirmationItem.change(function() {
                if(this.checked) {
                    $(':input[type="submit"]').prop('disabled', false);
                } else {
                    $(':input[type="submit"]').prop('disabled', true);
                }
            });
        });

        function showAuthorization() {
            $('#id_review').hide();
            $('#id_authorization_form').show();
            $(window).scrollTop(0);
        }

    </script>

@endsection
