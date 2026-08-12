@php
$states = \App\Models\State::getCodeListUSA();
$countries = \App\Models\Country::getListUSAOnly();
$phoneTypes = \App\Models\PhoneType::selectContactPhoneTypes();
$prefixes = \App\Models\Prefix::getSelectable();
$maxIndividuals = \App\Models\ClientConfig::value('DAF_MAX_SUCCESSORS_INDIVIDUALS');
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
                        <p class="form-title">Individuals</p>
                    </div>

                    @include('donor.registration._form-successors_allocation', compact($successorDesignation = false))

                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>
                    <div >

                        @foreach($individuals as $i => $individual)

                            @if ($individual->isNew && count($individuals) > 1)
                                @if ( count($individuals)-1 < $maxIndividuals )
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="javascript:void(0);" id="id_add_individual_btn" onclick="addIndividual();">
                                                <i class="fas fa-plus-circle"></i> Add more</a>
                                        </div>
                                    </div>
                                @endif

                                <div id="id_add_individual" style="display: none" class="daf-form-card" >
                                    @include('donor.registration._form_individuals', compact('individual'))
                                </div>

                            @else
                                <div id="{{'id_individual_' . $individual->key}}" class="daf-form-card mb-4" >

                                    @include("donor.registration._form_individuals", compact('individual'))
                                </div>
                            @endif

                        @endforeach
                    </div>

                    @include(\App\Models\ClientInfo::clientViewFor("registration.help-footer-successors", "donor."))

                </div>
            </div>
        </div>
    </div>
    <script>
        function addIndividual() {
            $('#id_add_individual').show(600);
            $('#id_add_individual_btn').fadeOut();
        }

        var total = '{{$givingTotal}}';
        if (total > 100) {
            $('#total_warning').html("<i class='fa fa-exclamation-triangle'></i> Total percentage greater than 100%.");
            $('#id_add_individual_btn').hide();
        }

    </script>
@endsection
