{{--for pfr only--}}
@php
$dafTypes = \App\Helpers\Data::getDAFTypes();
@endphp
@extends ('agency.agency-advisor.daf-registration.main')

@section('content')

    @include('common.page-header', ['pageTitle' => \App\Models\DAFAccount::DAF_TYPE_LABEL, 'split84' => true])

    <style>
        .large-radio {
            transform: scale(1.5); /* Makes the radio button larger */
            margin-right: 5px;
        }
    </style>

    <div class="container pageTop">
        <div class="form-wrapper form-last custom-form">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <p class="form-title">Desired Type of Donor-Advised Fund</p>
                    </div>

                    <form method="POST" action="{{ route('agency-save-daf-type', $id) }}" id="id-daf-type-form">
                        @csrf

                    @foreach($dafTypes as $key => $label)
                        <div class="form-group d-flex align-items-start">
                            <input type="radio" name="daf_type" id="id-{{ $key }}"
                                   class="radio-1x mt-2 large-radio" value="{{ $key }}"
                                   {{ old('daf_type', $model->daf_type ?? '') == $key ? 'checked' : '' }}>
                            <div class="ml-2">
                                <label for="id-{{ $key }}">{{ $label }}</label>

                                @if($key == 'traditional_daf')
                                    <p class="text-sm">
                                        Grant recommendations done at the timing and in the amount requested by the Fund Advisor(s). This provides the most flexibility in making grants.
                                    </p>
                                    <hr>
                                @elseif($key == 'spending_policy_daf')
                                    <p class="text-sm">
                                        Grant Recommendations done by the Fund Advisor(s) at least annually up to the annual spending policy. The spending policy is established by Provision and is currently 4% of the market value as of the prior year-end. This provides the option for annual grants with a goal to preserve capital.
                                    </p>
                                    <hr>
                                @elseif($key == 'render_daf')
                                    <p class="text-sm">
                                        For those wishing to execute their Christian philanthropic goals through investments in Provision’s Render Capital Fund. Certain minimums and liquidity restrictions apply. Please refer to Provision’s Program Guide or reach out to our team for more information.
                                    </p>
                                @else
                                    <p class="text-sm">
                                        No Description
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endforeach

                    <div class="form-btn-bar" id="form-footer" style="display: none;">
                        <div class="col-md-12 form-footer">
                            <div class="row">
                                <p class="offset-md-3 col-md-3">
                                    <button type="submit" name="save" id="id_save_btn" class="btn btn-accent w100">SAVE</button>
                                </p>
                                <p class="col-md-3">
                                    <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
                                </p>
                            </div>
                        </div>
                    </div>

                    </form>
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-daf-type", "agency.agency-advisor."))
                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-account-info", "agency.agency-advisor."))
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Function to check if any radio is checked and toggle the footer
            function toggleFooter() {
                if ($('input[name="daf_type"]:checked').length > 0) {
                    $('#form-footer').show(); // Show the footer when any radio is checked
                } else {
                    $('#form-footer').hide(); // Hide the footer when no radio is checked
                }
            }

            // Check if a radio button is already selected when the page loads
            toggleFooter();

            // Bind the change event to radio buttons
            $('input[name="daf_type"]').on('change', function () {
                toggleFooter(); // Call toggleFooter when any radio button changes
            });
        });
    </script>

@endsection
