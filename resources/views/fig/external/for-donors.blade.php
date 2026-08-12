
@extends('donor.layouts.main', ['container' => '', 'external' => true, 'navbar' => \App\Models\ClientInfo::customNav()])

@section('content')

    <div class="container ext-info-page">
        <div class="row">
            <div class="col-sm-6">

                <div class="text-left">
                    <h1 class="title">The Perfect Giving Solution For Donors.</h1>
                </div>

                <div class="text-left">

                    <p>Perfect, because once your donor-advised fund is established, you will have access to My Inspired Giving, a safe and secure platform where you can support the causes for which you are passionate</p>

                    <ul>
                        <li><span class="fw600">More Choice</span> — The flexibility of My Inspired Giving’ proprietary Give Now, Give Later, Give ForeverTM feature allows you to determine the amount, frequency and distribution of your contributions, immediately or through your donor-advised fund.</li>
                        <li><span class="fw600">Potential Tax Savings</span> — Talk to your financial advisor about the tax benefits associated with donor-advised funds.</li>
                        <li><span class="fw600">Greater Convenience</span> — Manage your giving 24/7 from any digital device with our best-in-class and easy-to-use web-based giving tool.</li>
                        <li><span class="fw600">Peace of Mind</span> — All investment made through My Inspired Giving are in FDIC and SPIC financial institutions. To further ensure security, My Inspired Giving adheres to strict privacy guidelines.</li>
                    </ul>

                </div>

            </div>

            <div class="col-sm-6">
                <div class="rp-image">
                    <img src="/ma/images/fig/ext-donor.jpg" />
                </div>
            </div>
        </div>
    </div>

@endsection

