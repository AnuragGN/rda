@php
$tTipIndividualsOrgs = null;
$tTipEndowment = null;
if (\App\Models\ClientInfo::isHGA()) {
    $tTipIndividualsOrgs = 'Individuals must make final grant recommendations or open a new donor-advised fund with their designated percentage of the remaining funds. Charitable Organizations receive their designated percentage of the remaining funds as a grant.';
    $tTipEndowment = 'With this strategy, remaining DAF funds are used to open an endowment that makes monthly distributions to charitable organizations. The DAF must contain a minimum of $25,000 to establish an endowment. Once the DAF application is approved, HighGround will ask Donor Advisors to designate up to four charitable beneficiaries that can be changed at any time.';
}
@endphp
@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')
    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFSuccessors::title()])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">
            <div class="row">

                <div class="col-md-8">
                   @include('agency.agency-advisor.daf-registration._form-successors_allocation', ['successorDesignation' => true])
                    <div class="form-group row pt-1">
                        <form method="POST" action="{{ route('post-agency-daf-successors', $id) }}"
                              id="id_form_endowment" class="form-horizontal offset-md-1">
                        @csrf

                        <div class="form-check form-check-inline ml-1">
                            <input type="radio" name="isSelected" id="id_individual_charitable"
                                   class="form-check-input" value="0"
                                   {{ (old('isSelected', optional($successor)->isSelected) != '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="id_individual_charitable">Individuals & Charitable Organizations
                            </label>

                            @include('common.tooltip-title-info', ['tooltipInfo' => $tTipIndividualsOrgs])
                        </div>
                        <div class="form-check form-check-inline ml-1">
                            <input type="radio" name="isSelected" id="id_endowment"
                                   class="form-check-input" value="1"
                                   {{ (old('isSelected', optional($successor)->isSelected) == '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="id_endowment">Establish a Permanent Endowment
                                @if(\App\Models\ClientInfo::isPFR())
                                    (if balance over $10,000) or contribute to The Provision OneFund.
                                @endif
                            </label>
                            @include('common.tooltip-title-info', ['tooltipInfo' => $tTipEndowment])
                        </div>
                    </div>
                    <div class="form-group">
                        @include('errors.form-errors')
                    </div>

                    <div  style="display: none" id="id_endowment_form">

                        @if(\App\Models\ClientInfo::isHGA())
                            <div class="row mt-4">
                                <div class="col-12">
                                    Establish a Permanent Endowment ($25,000 minimum)
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-2">
                            <label for="id_endowment_name" class="offset-md-1 pl-2 col-form-label text-right pr-0">Name of Endowment</label>
                            <div class="col-md-6">
                                <input type="text" name="endowment_name" id="id_endowment_name"
                                       class="form-control"
                                       onkeypress="return /^[A-Za-z\s]*$/i.test(event.key)"
                                       value="{{ old('endowment_name') }}">
                            </div>

                            @if(\App\Models\ClientInfo::isPFR())
                                <p class="offset-md-1 pl-2 mt-2">
                                    Endowments can be setup by calling our offices at (919) 380-7334.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="form-btn-bar">
                        <div class="col-md-12 form-footer">
                            <div class="row">
                                <p class="offset-md-1 col-md-3">
                                    <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
                                </p>

                                <p class="col-md-3" id="id_save_next_btn">
                                    <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
                                </p>

                            </div>
                        </div>
                    </div>

                    </form>

                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-successors", "agency.agency-advisor."))

                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            var radio = $("input[id='id_endowment']:checked").val();
            if(radio) {
                $('#id_endowment_form').show();
                $('#id_save_next_btn').show();
            } else {
                $('#id_save_next_btn').hide();
            }

            $('input:radio[name="isSelected"]').change(
                    function(){
                        if ($(this).is(':checked') && $(this).val() == true) {
                            $('#id_endowment_form').show(300);
                            $('#id_save_next_btn').show(300);
                        } else {
                            $('#id_endowment_form').hide(300);
                            $('#id_save_next_btn').hide(300);
                        }
                    });
        });
    </script>

@endsection