@php
$maxAdditionalDonors = App\Models\ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR');
$givingTotal = App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
$contributionTypes = \App\Helpers\Data::getContributionTypes();
@endphp

        <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            {{--<a href="{{ route('profile') }}" class="nav-link">{{ $user->first_name }}</a>--}}
            <a class="nav-link">{{ $user->name() }}</a>
        </li>
    </ul>
</nav>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 gn-main-sidebar">

    <!-- Brand Logo -->
    @include( \App\Models\ClientInfo::clientViewFor('layouts.sidebar-logo'))

            <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{--<div class="user-panel mt-3 pb-3 mb-3 d-flex d-block d-sm-none">--}}
            {{--<div class="image" style="font-size: 22px">--}}
                {{--<i class="far fa-user"></i>--}}
            {{--</div>--}}
            {{--<div class="info">--}}
                {{--<a href="{{ route('profile') }}" class="d-block">{{ $user->first_name }}</a>--}}
            {{--</div>--}}
        {{--</div>--}}
        <!-- Sidebar Menu -->
        <nav class="mt-4">

            <style>
                .link-status i {
                    float: right;
                    padding-top: 5px;
                }
            </style>
            {{--<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">--}}
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent2 nav-compact nav-legacy nav--flatv-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                {{--<hr class="hr-divider">--}}
                @if($linkAuthorization != \App\Models\DAFAccount::LINK_SAVED)

                    <li class="nav-header" style="padding-left: 1rem"><small>Application Steps</small></li>
                    <hr class="hr-divider">

                    <li class="nav-item">
                        <a href="{{route('daf-account-info', $id)}}" class="nav-link">
                            <i class="far fa-edit"></i>
                            <p>{{ \App\Models\DAF\DAFDonor::title() }}</p>
                            <p>
                                @include("donor.registration.menu-item-status", ['status' => $linkDonor])
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        @if($linkAdditionalDonor == \App\Models\DAFAccount::LINK_DISABLED)
                            <a  class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>{{\App\Models\DAF\DAFAdditionalDonor::title()}}</p>
                            </a>
                        @else
                            @if ($additionalDonors && $linkAdditionalDonor != \App\Models\DAFAccount::LINK_DISABLED)
                                <span href="javascript:void(0);"
                                    class="nav-link">
                                    <i class="far fa-edit"></i>
                                    <p>{{\App\Models\DAF\DAFAdditionalDonor::title()}}</p>
                                    @include("donor.registration.menu-item-status", ['status' => $linkAdditionalDonor])
                                </span>
                            @else
                                <a  href="javascript:void(0);"
                                    class="nav-link">
                                    <i class="far fa-edit"></i>
                                    <p>{{\App\Models\DAF\DAFAdditionalDonor::title()}}</p>
                                    @include("donor.registration.menu-item-status", ['status' => $linkAdditionalDonor])
                                </a>
                            @endif
                        @endif
                        <ul class="nav">
                            @if ($additionalDonors && $linkAdditionalDonor != \App\Models\DAFAccount::LINK_DISABLED)

                                @foreach ($additionalDonors as $donor => $name)
                                 
                                    <li class="nav-item">
                                        <a href="{{route('daf-additional-donor',['key' => $name['key'], 'id' => $id])}}" class="nav-link">
                                            {{--<i class='fa fa-user-circle'></i>--}}
                                            <i class='fas fa-user'></i>
                                            <p>{{$name['first_name']}}</p>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            @if ( count($additionalDonors) < $maxAdditionalDonors && $linkAdditionalDonor != \App\Models\DAFAccount::LINK_DISABLED)
                                <li class="nav-item">
                                    <a href="{{route('daf-additional-donor', $id)}}" class="nav-link">
                                        <i class='fa fa-user-plus'></i>
                                        <p>Add</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item">
                        @if($linkSuccessors == \App\Models\DAFAccount::LINK_DISABLED)
                            <a class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>{{ \App\Models\DAF\DAFSuccessors::title() }}</p>
                            </a>
                        @else
                            <a href="{{route('daf-successors', $id)}}"
                               class="nav-link" id="id_successor_menu">
                                <i class="far fa-edit"></i>
                                <p>{{ \App\Models\DAF\DAFSuccessors::title() }}</p>
                                <span class="link-status" id="id_successor_link_status">
                                    @if($givingTotal > 100)
                                        <i class='fa fa-exclamation-triangle' style='color: #f1f1f1'></i>
                                    @else
                                        @include("donor.registration.menu-item-status", ['status' => $linkSuccessors])
                                    @endif
                                </span>
                            </a>

                            @if( !$endowmentSelected && $endowmentInfo )

                                <ul class="nav">
                                    <li class="nav-item">
                                        <a href="{{ route('daf-successors-individuals', $id) }}"
                                           class="nav-link" id="id_individuals">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                            <p>Individuals</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('daf-successors-organizations', $id) }}"
                                           class="nav-link">
                                            <i class="fa fa-university" aria-hidden="true"></i>
                                            <p>Charitable Organizations</p>
                                        </a>
                                    </li>
                                </ul>

                            @endif
                        @endif
                    </li>

                    <li class="nav-item">
                        @if($linkContributions == \App\Models\DAFAccount::LINK_DISABLED)
                            <a class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>Contributions</p>
                            </a>
                        @else
                            <a href="javascript:void(0);"
                               class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>Contributions</p>
                                @include("donor.registration.menu-item-status", ['status' => $linkContributions])
                            </a>
                        @endif
                        <ul class="nav">
                            @if ($linkContributions == \App\Models\DAFAccount::LINK_DISABLED)
                                <li class="nav-item">
                                    <a class="nav-link disabled-link">
                                        {{--<i class="nav-icon far fa-edit"></i>--}}
                                        <i class='fas fa-money-check-alt'></i>
                                        <p>Cash Equivalents</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled-link">
                                        <i class='fas fa-money-check'></i>
                                        <p>Securities or Mutual Funds</p>
                                    </a>
                                </li>

                                @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_STOCKS, $contributionTypes) )
                                    <li class="nav-item">
                                        <a class="nav-link disabled-link">
                                            <i class='fas fa-money-check'></i>
                                            <p>Stock Certificates</p>
                                        </a>
                                    </li>
                                @endif

                                @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_OTHERS, $contributionTypes) )
                                    <li class="nav-item">
                                        <a class="nav-link disabled-link">
                                            <i class='fas fa-money-check'></i>
                                            <p>Others</p>
                                        </a>
                                    </li>
                                @endif

                            @else
                                <li class="nav-item">
                                    <a href="{{route('daf-contributions-cash', $id)}}"
                                       class="nav-link ">
                                        <i class='fas fa-money-check-alt'></i>
                                        <p>Cash Equivalents</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('daf-contributions-securities', $id)}}" class="nav-link">
                                        <i class='fas fa-money-check'></i>
                                        <p>Securities or Mutual Funds</p>
                                    </a>
                                </li>

                                @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_STOCKS, $contributionTypes) )
                                    <li class="nav-item">
                                        <a href="{{route('daf-contributions-stocks', $id)}}" class="nav-link">
                                            <i class='fas fa-money-check'></i>
                                            <p>Stock Certificates</p>
                                        </a>
                                    </li>
                                @endif
                                @if( in_array(App\Helpers\Data::DAFR_DONOR_CONTRIBUTIONS_OTHERS, $contributionTypes) )
                                    <li class="nav-item">
                                        <a href="{{route('daf-contributions-others', $id)}}" class="nav-link">
                                            <i class='fas fa-money-check'></i>
                                            <p>Others</p>
                                        </a>
                                    </li>
                                @endif
                            @endif

                        </ul>
                    </li>

                    @if($linkInvestments == \App\Models\DAFAccount::LINK_DISABLED)
                        <li class="nav-item">
                            <a class="nav-link disabled-link">
                                <i class="far fa-edit"></i>
                                <p>{{\App\Models\DAF\DAFInvestment::title()}}</p>
                                {{--@include("donor.registration.menu-item-status", ['status' => $linkInvestments])--}}
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{route('daf-investments', $id)}}"
                               class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>{{\App\Models\DAF\DAFInvestment::title()}}</p>
                                @include("donor.registration.menu-item-status", ['status' => $linkInvestments])
                            </a>
                        </li>
                    @endif
                    @if($linkAuthorization == \App\Models\DAFAccount::LINK_DISABLED)
                        <li class="nav-item">
                            <a class="nav-link disabled-link">
                                <i class="far fa-edit"></i>
                                <p>{{ \App\Models\DAFAccount::reviewTitle() }}</p>
                                {{--@include("donor.registration.menu-item-status", ['status' => $linkAuthorization])--}}
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{route('daf-authorization', $id)}}"
                               class="nav-link">
                                <i class="far fa-edit"></i>
                                <p>{{ \App\Models\DAFAccount::reviewTitle() }}</p>
                                @include("donor.registration.menu-item-status", ['status' => $linkAuthorization])
                            </a>
                        </li>
                    @endif

                @else
                    <li class="nav-header" style="padding-left: 1rem"><small>Application Steps</small></li>
                    <hr class="hr-divider">
                    <li class="nav-item">
                        <a href="{{route('daf-application-status', $id)}}" class="nav-link">
                            <i class="far fa-edit"></i>
                            <p>Completed Application</p>
                        </a>
                    </li>
                @endif
                <li class="nav-header" style="padding-left: 1rem"><small>My Profile</small></li>
                <li class="nav-item">
                    <a href="{{route('daf-change-password-form', $id)}}" class="nav-link">
                        <i class="far fa-edit"></i>
                        <p>Change Password</p>
                    </a>
                </li>

                @include('utils._form_logout', ['class' => 'dropdown-item'])
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link" onclick="onLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
            <br>
            <br>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });

    $(document).ready(function(){
        //  $('a:not([href])').css("background-color", "#6c6c6c");
        $('a:not([href]) p').css("color", "#dfdfdf");
        $('a:not([href]) i').css("color", "#dfdfdf");
        $('a:not([href])').css("cursor", 'default');
    });
</script>

{{--<script>--}}
    {{--setTimeout(function() {--}}
        {{--$(".flash-message").hide();--}}
    {{--}, 4000);--}}
{{--</script>--}}
