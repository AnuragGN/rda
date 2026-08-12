
@extends('donor.layouts.main', ['container' => '', 'external' => true, 'navbar' => \App\Models\ClientInfo::customNav()])

@section('content')

    <div class="container ext-info-page">
        <div class="row">
            <div class="col-sm-6">

                <div class="text-left">
                    <h1 class="title">The Best Giving Platform For Financial Advisors.</h1>
                </div>

                <div class="text-left">

                    <p>Best, because My Inspired Giving recognizes the value you provide to your clients, our giving solution to allows you to continue to serve their needs.</p>

                    <ul>
                        <li><span class="fw600">Stronger Client Relationships</span> — Once clients establish their donor-advised fund, they authorize you to advise them on their philanthropic investments. Your involvement is a value to your clients and My Inspired Giving honors that relationship.</li>

                        <li><span class="fw600">Ongoing Client Support</span> — Whether its setting up donor-advised funds or making allocation changes, clients will appreciate your counsel on fulfilling their purpose through better philanthropic investments.</li>

                        <li><span class="fw600">Easy Administration</span> — Log in to your My Inspired Giving account to see all your clients’ donor-advised fund activity in one site.</li>
                    </ul>
                </div>

            </div>

            <div class="col-sm-6">
                <div class="rp-image">
                    <img src="/ma/images/fig/ext-advisor.jpg" />
                </div>
            </div>

        </div>
    </div>

@endsection

