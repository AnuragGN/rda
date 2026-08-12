@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Make a Gift'])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">

                <div class="col-xl-9">
                    <h3 class="page-subtitle mt-2"><span>
                        <a href="/ma/docs/cct/CCFGiftingInstructions.pdf" target="_blank">
                            Download Gift Instructions <i class="fas fa-download" style="font-size: 80%"></i>
                        </a>
                    </span></h3>


                    <p>The above PDF Gift Instructions form provides information on adding to your fund by contributing
                        appreciated securities, wire or ACH tranfers, or through credit card or check donations.</p>

                    <h3 class="page-subtitle"><span>
                        <a href="https://cct.secure.force.com/pmtx/dn8n__SiteDonation?id=a3E0h000001beLr" target="_blank">Click Here</a>
                        to make an online gift to a donor advised fund
                    </span></h3>
                    <p>The above link enables you to make a contribution to any donor advised fund.
                    Please note: this link will open a new tab for secure donation processing.
                    Your credit card information will not be saved or stored in Instant Impact.</p>

                    To make giving simple, donations from all major credit cards are accepted. Gifts by credit card
                    incur a flat processing fee of 2.6%.

                    <br><br>
                </div>

                {{-- TODO: for NT Only--}}
                <div class="col-xl-9">
                    <h3 class="page-subtitle">Contribution Form<small><i style="color:red;">(For NT only)</i></small></h3>
                    <h5 class="mt-4 mb-5">
                        <a href="/ma/docs/cct/DAFAdditionalContributionFillableForm.pdf" target="_blank">
                            Download Additional Contribution Form <i class="fas fa-download"></i>
                        </a>
                    </h5>
                    <br>
                </div>

            </div>
        </div>
    </div>

@endsection
