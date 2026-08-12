@extends('agency.agency-advisor.registration.main-account')

@section('content')

    <?php $supportEmail = config('mail.from.address', 'support@yourdomain.com'); ?>

    <style>
        .advisor-page * {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        }

        .advisor-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #1a1a1a;
        }

        .advisor-text {
            font-size: 1rem;
            color: #3d3d3d;
            line-height: 1.7;
        }

        .advisor-section-title {
            font-weight: 600;
            color: #000;
            margin-bottom: .35rem;
        }

        .advisor-help-box {
            background: #fcfcfc;
            border: 1px solid #e2e2e2;
            border-radius: 6px;
            padding: 18px 20px;
            margin-top: 20px;
        }

        .advisor-help-box ul {
            padding-left: 18px;
            margin-bottom: 0;
        }

        .advisor-footer-text {
            font-size: .9rem;
            color: #6c757d;
        }

        .advisor-link {
            color: #0060df;
            text-decoration: underline;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            font-size: .95rem;
            padding: 8px 18px;
            border: 1px solid #0060df;
            color: #0060df;
            border-radius: 4px;
            transition: .3s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #0060df;
            color: #fff;
        }
    </style>

    <div class="advisor-page container custom-form pageTop mt-5">
        <div class="form-body">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-10">

                        <h3 class="advisor-title mb-3">
                            We couldn’t verify your login details
                        </h3>

                        <p class="advisor-text">
                            We couldn’t confirm your user information. This may happen if a valid User ID / SSO ID was not
                            received, or the details do not match what we have in our system.
                        </p>

                        <div class="advisor-help-box">
                            <p class="advisor-section-title">How to resolve</p>
                            <ul class="advisor-text">
                                <li>Try logging in again through your identity provider’s portal.</li>
                                <li>Make sure your SSO details are correctly set up and up to date.</li>
                                <li>If the issue continues, please reach out to your administrator for support.</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <p class="advisor-section-title">Need assistance?</p>
                            <p class="advisor-footer-text">
                                Contact our support team at
                                <a class="advisor-link" href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.  
                                We’re here to help.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
