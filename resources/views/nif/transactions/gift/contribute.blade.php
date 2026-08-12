@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Contributing to your DAF'])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-9">

                    <div class="fw600">Check</div>
                    <ul style="padding-left: 1.2rem;">
                        <li>Please send the check to: New Israel Fund; P.O. Box 70358; Philadelphia, PA 19176-0358</li>
                        <li>Write the name of your DAF on the memo line</li>
                    </ul>

                    <div class="fw600">Credit Card</div>
                    <ul style="padding-left: 1.2rem;">
                        <li>You can make a credit card contribution to your DAF
                            <a target="_blank" href="https://secure.nif.org/onlineactions/Mz6TpJeV1Eer0cPmdi0gow2?ms=top%20donate%20nav%20submenu" class="click_here_ul">here</a>.</li>
                        <li>Make the gift “in honor of” the name of your fund.</li>
                    </ul>

                    <div class="fw600">Wire Transfer/ACH Transfer</div>
                    <ul style="padding-left: 1.2rem;">
                        <li>Please notify us at pjf@nif.org of your incoming wire.</li>
                        <li class="fw600">Please ask your bank to include a memo on the transfer slip that identifies you as the donor.</li>
                        <li>Contributions may be wired directly to NIF at:
                            <br><span  class="fw600">Name of bank to wire funds:</span>
                            First Republic Bank
                            <br><span  class="fw600">Bank address:</span>
                            <br>First Republic Bank
                            <br>111 Pine Street
                            <br>San Francisco, CA 94111
                            <br><span  class="fw600">ABA / Routing #:</span> 321 081 669
                            <br><span  class="fw600">Account #:</span> 80008866339
                            <br><span  class="fw600">Name on the Account:</span>
                            <br>New Israel Fund (Operating)
                            <br><span  class="fw600">SWIFT Code (International Transfers):</span>
                            <br>FRBBUS6S

                        </li>
                    </ul>

                    <div class="fw600">Stock Transfer</div>
                    <ul style="padding-left: 1.2rem;">
                        <li>Please follow the instructions
                            <a target="_blank" href="https://www.nif.org/get-involved/ways-to-give/why-make-a-gift-of-stock/" class="click_here_ul">here</a>.</li>
                        <li class="fw600">Be sure to notify us of regarding the name of securities and number of shares being sent, as gifts of stock do not arrive with accompanying information.</li>
                    </ul>

                    <div class="fw600">Crypto Currency</div>
                    <ul style="padding-left: 1.2rem;">
                        <li>Gifts of Crypto Currency can be made
                            <a target="_blank" href="https://app.endaoment.org/orgs/942607722" class="click_here_ul">here</a>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
