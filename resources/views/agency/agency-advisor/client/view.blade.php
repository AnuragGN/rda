@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => "container history-container", 'agencyContainer' => "container history-container"])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Client Profile', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper2 form-last2">
                <div class="row profile-view">
                    <div class="col-md-12">
                        <div class="col-xl-9">
                            <div class="card gn-shadow profile-info">
                                <div class="header">
                                    <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_contact_info_view">
                                        <span class="open"><i class="fas fa-caret-down"></i></span>
                                        <span class="closed"><i class="fas fa-caret-right"></i></span>
                                        Contact Info
                                    </div>
                                </div>
                                <div class="body" id="id_contact_info_view">
                                    <div class="mb-2" style="display: flex; justify-content: space-between;">
                                        <span>
                                            <div>{{$profile->fullname}}</div>
                                            @if($profile->company_name)
                                                <div><label>Company</label> {{$profile->company_name}}</div>
                                            @endif
                                            @if($profile->web_site)
                                                <div><label>Website</label> {{$profile->web_site}}</div>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mb-2" style="display: flex; justify-content: space-between;">
                                        <span>
                                            <label>Email</label> {{$profile->email_address}}
                                        </span>
                                    </div>
                                    @include('agency.agency-advisor.client.view_phones')
                                </div>
                            </div>
                        </div>
                        @include('agency.agency-advisor.client.view_addresses')
                        @include('agency.agency-advisor.client.view_funds')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
