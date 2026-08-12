@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Forms'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-8">
                    <h3 class="page-subtitle uppercase"> General Forms</h3>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/5ec305f5-0f25-40c0-823e-f13164fb3517?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Application Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                        {{--<p> Use this form to create additional donor-advised funds.</p>--}}
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <p> Use this form to create additional donor-advised funds.</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/06d47ee0-ae3e-499a-be96-bcfae85612af?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Contribution Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <p>  Use this form to make additional contributions to a donor-advised fund. Contributions by credit card or e-check can be made online under the Contribute Tab.</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/abf3273c-b691-4d22-94db-b24f0a97e9fb?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Grant Recommendation Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                        <div class="col-xl-12">
                            <p> Use this form to make grant recommendations from your donor-advised fund. Grant recommendations can be made online under the Grant Tab.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <h3 class="page-subtitle uppercase"> Change Forms</h3>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/acb4c774-0c44-416a-a4c3-58c3b2d1980e?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Donor-Advised Fund Change Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                        <div class="col-xl-12">
                            <p>Use this form to make any changes to your donor-advised fund name, donor advisor and/or interested party information.</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/d204711a-5eab-437a-ad21-639ee652d91e?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Succession Strategy Change Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                        <div class="col-xl-12">
                            <p>Use this form to make changes to your succession plan.</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-6">
                            <a target="_blank" href="https://powerforms.docusign.net/eb7a409a-52a7-48cd-ad34-f72acc66d347?env=na3&acct=22dd265e-b729-437f-abeb-882394f0ac00&accountId=22dd265e-b729-437f-abeb-882394f0ac00">
                                Investment Fund Change Form
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </label>
                        <div class="col-xl-12">
                            <p> Use this form to make any changes to your donor-advised fund investment options or visit the Invest Tab online.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
