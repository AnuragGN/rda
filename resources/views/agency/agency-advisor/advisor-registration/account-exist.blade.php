@extends('agency.agency-advisor.advisor-registration.main-account')

@section('content')

    @php $supportEmail = config('mail.from.address') ?? 'support@yourdomain.com'; @endphp

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
                            Your advisor account already exists — Activation Pending
                        </h3>

                        <p class="advisor-text">
                            An advisor account with your information already exists in our system.  
                            However, the account is not yet active because it is still awaiting administrative approval.
                        </p>

                        <div class="advisor-help-box">
                            <p class="advisor-section-title">What happens next</p>
                            <ul class="advisor-text">
                                <li>An administrator will review your account details.</li>
                                <li>You will receive an email notification as soon as your account is approved and activated.</li>
                                <li>If any additional information is needed, we will contact you using the email address on file.</li>
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
