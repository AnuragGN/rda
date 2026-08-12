<?php
if (!isset($container)) $container = 'container';
$dafRegistrationPage = true;

if (isset($dafInfo)) {

    $status = $dafInfo['status'];
    $linkDonor = $status['donor'];
    $linkAdditionalDonor = $status['additional_donor'];
    $linkSuccessors = $status['successors'];
    $linkContributions = $status['contributions'];
    $linkInvestments = $status['investments'];
    $linkAuthorization = $status['authorization'];
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

@include('donor.layouts.main-head')

<body class="sidebar-mini layout-fixed layout-navbar-fixed">

@include('donor.layouts.flash-box')

<div class="wrapper">

    @include('donor.registration.admin-lte-nav')

    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">

        @include('donor.layouts.session-status')

        @yield('content')

    </div>

    @include(\App\Models\ClientInfo::clientViewFor('layouts.main-footer'))

</div>

@yield('footer-scripts')

@include('donor.layouts.main-scripts')

</body>

</html>
