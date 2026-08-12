<?php
if (!isset($container)) $container = 'container';
$dafRegistrationPage = true;

if (isset($dafInfo)) {

    $status = $dafInfo['status'];
    $linkDonor = $status['donor'];
    $linkAdditionalDonor = $status['additional_donor'];
    $linkDAFType = @$status['daf_type'];
    $linkSuccessors = $status['successors'];
    $linkContributions = $status['contributions'];
    $linkInvestments = $status['investments'];
    $linkAuthorization = $status['authorization'];
    $authorized = $dafInfo['authorized'] ?? false;

    //$donors =   App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_ADDITIONAL_DONOR, $id);
    // $additionalDonors = isset($donors['donors'])? $donors['donors'] : '';

    $additionalDonors = \App\Models\DAFAccount::getAdditionalDonorsList($id);

    $successors =   App\Models\DAFAccount::getDAFInfo(\App\Models\DAFAccount::DAF_SUCCESSORS, $id);
    $endowmentInfo =  isset($successors['endowment']) ? $successors['endowment'] : null;
    if ($endowmentInfo) {
        $endowmentSelected = $successors['endowment']['isSelected'];
    } else {
        $endowmentSelected = $endowmentInfo;
    }
}

?>

<!DOCTYPE html>
<html>

@include('agency.layouts.main-head')

<body class="sidebar-mini layout-fixed layout-navbar-fixed {{ \App\Models\FaPartner::getBrandBodyClasses() }}">

@include('agency.layouts.flash-box')

<div class="wrapper">

    @include('agency.agency-advisor.daf-registration.admin-lte-nav-new')
    {{-- @include('agency.agency-advisor.daf-registration.admin-lte-nav-new') --}}

    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">

        @include('agency.layouts.session-status')

        @yield('content')

    </div>
	@include('agency.layouts.main-footer')
</div>

@yield('footer-scripts')

@include('agency.layouts.main-scripts')

</body>

</html>
