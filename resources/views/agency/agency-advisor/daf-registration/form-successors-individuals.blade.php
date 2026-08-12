@php
$maxIndividuals = \App\Models\ClientConfig::value('DAF_MAX_SUCCESSORS_INDIVIDUALS');
$givingTotal = App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
@endphp
@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFSuccessors::title()])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">

                <div class="col-md-8">
                    <div class="form-group">
                        <p class="form-title">Individuals
                            <a href="{{route($nextRedirectUrl, $id)}}" class="btn btn-sm btn-light col-sm-2" style="float: right">Skip</a>
                        </p>
                    </div>

                    @include('agency.agency-advisor.daf-registration._form-successors_allocation', ['successorDesignation' => true])

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
                                    @include('agency.agency-advisor.daf-registration._form_individuals', compact('individual'))
                                </div>

                            @else
                                <div id="{{'id_individual_' . $individual->key}}" class="daf-form-card mb-4" >

                                    @include("agency.agency-advisor.daf-registration._form_individuals", compact('individual'))
                                </div>
                            @endif

                        @endforeach
                    </div>

                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-successors", "agency.agency-advisor."))

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
