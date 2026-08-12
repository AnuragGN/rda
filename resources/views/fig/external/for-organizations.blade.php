
@extends('donor.layouts.main', ['container' => '', 'external' => true, 'navbar' => \App\Models\ClientInfo::customNav()])

@section('content')

    <div class="container ext-info-page">
        <div class="row">
            <div class="col-sm-6">

                <h1 class="title">The Most Effective Giving Platform For Charitable Organizations.</h1>

                <div class="text-left">

                    <p>Effective, because coupling the advantages of donor-advised funds with best in class technology and bundling it all under your brand will result in better fundraising outcomes for your organization.</p>

                    <ul>
                        <li><span class="fw600">Build Brand Equity</span> — My Inspired Giving is not a generic giving platform. It will be custom branded with your colors, logo and language. Simply stated, it will be YOUR giving platform that your donors will appreciate as an added value service you provide to them.</li>
                        <li><span class="fw600">Generate More Revenue</span> — Our proprietary Give Now, Give Later, Give ForeverTM platform will result in increasing the number of donors contributing on a recurring basis.</li>
                        <li><span class="fw600">Reduce Administrative Costs</span> — Your organization receives ongoing donor grants with full documentation. Quarterly and annual tax statements are also provided.</li>
                    </ul>
                </div>

            </div>

            <div class="col-sm-6">
                <div class="rp-image">
                    <img src="/ma/images/fig/ext-org.jpg" />
                </div>
            </div>

        </div>
    </div>

@endsection

