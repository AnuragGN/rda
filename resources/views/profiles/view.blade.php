@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), \App\Helpers\GnUtils::isDonorSession() ? ['container' => 'none'] : [])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'My Profile', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper2 form-last2">
                <div class="row profile-view">
                    <div class="col-xl-9">

                        <div class="card gn-shadow profile-info">
                            <div class="header">
                                <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_contact_info_view">
                                    <span class="open"><i class="fas fa-caret-down"></i></span>
                                    <span class="closed"><i class="fas fa-caret-right"></i></span>
                                    Contact Info</div>
                                <div><a class="hide" href="{{route('profile-edit')}}">Edit</a></div>
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
                                    <a href="{{route('profile-edit')}}" class="txt-btn-sm">EDIT</a>
                                </div>

                                <div class="mb-2" style="display: flex; justify-content: space-between;">
                                    <span>
                                        <label>Email</label> {{$profile->email_address}}
                                    </span>
                                    <a href="{{route('change-email-form')}}" style="font-size: 13px; font-weight: 600;">CHANGE EMAIL</a>
                                </div>

                                

                                @include('profiles.view_phones')
                               

                            </div>
                        </div>
                        
                        @include('profiles.view_addresses')

                        @include('profiles.view_profile_picture')

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


