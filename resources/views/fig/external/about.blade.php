
@extends('donor.layouts.main', ['container' => '', 'external' => true, 'navbar' => \App\Models\ClientInfo::customNav()])

@section('content')

    <div class="container ext-info-page">
        <div class="row">
            <div class="col-sm-4">
                <div class="text-center">
                    <h1 class="title">About Us</h1>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="text-left">
                    <h2 class="title">Our Mission</h2>
                    <p>The Community Foundation for Inspired Giving supports the work of charitable organizations by connecting them with donors and their financial advisors through My Inspired Giving. This engaging and empowering platform is designed to provide a consistent and ongoing level of financial support for organizations with whom we partner. The symbiotic relationship created through My Inspired Giving benefits all — organizations, donors and advisors — to work together for the greater good.</p>

                    <br>
                    <h2 class="title">Donor-Advised Funds</h2>
                    <p>Core to achieving our mission is offering individuals access to one of today’s fastest growing philanthropic tools for charitable giving — donor-advised fund. These easy-to-establish, low cost, flexible accounts are ideally suited for tax legislation implemented in 2017. In addition, donors benefit from the administrative convenience, cost savings and tax advantages associated with donor-advised funds.</p>

                    <br>
                    <h2 class="title">Give Now, Give Later, Give Forever™</h2>
                    This unique My Inspired Giving feature empowers donors to manage donations in multiple ways:

                    <ul>
                        <li><span class="fw600">Give Now:</span> Direct funds in response to an immediate need of an organization.</li>

                        <li><span class="fw600">Give Later:</span> Provides ongoing and long-term administration of donor-advised fund so donors can support the causes they care about into the future.</li>

                        <li><span class="fw600">Give Forever:</span> Empowers donors to leave a legacy by endowing their donor-advised fund or establishing an endowment or a foundation in their name, family or other designation.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

