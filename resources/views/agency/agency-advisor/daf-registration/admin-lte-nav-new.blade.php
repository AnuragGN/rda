<?php
$maxAdditionalDonors = App\Models\ClientConfig::value('DAF_MAX_ADDITIONAL_DONOR');
$givingTotal = App\Models\DAFAccount::getTotalIndividualOrgPercent($id);
$contributionTypes = \App\Helpers\Data::getContributionTypes();
?>
<?php
if (!isset($contact) || !$contact) $contact = \App\Models\Contact::sessionContact();
if (!isset($errorPage)) $errorPage = false;
if (!isset($contactId) || !$contactId) $contactId = \App\Models\Contact::sessionContactId();


// Initialize $contactTypeId to handle the result
$contactTypeId = null;
$contactType = null;

if (isset($contactId) && $contactId) {
    // Fetch the contact_type_id from the contact_type_contact table
    $contactTypeContact = \App\Models\ContactTypeContact::where('contact_id', $contactId)->first();

    if ($contactTypeContact) {
        $contactTypeId = $contactTypeContact->contact_type_id;
    } else {
        // Handle the case where no matching record is found
        $contactTypeId = null;
    }
}

// Fetch the contact_type from the contact_type table using $contactTypeId
if ($contactTypeId) {
    $contactType = \App\Models\ContactType::find($contactTypeId);
}
?>
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
            <a href="{{ route('profile') }}" class="nav-link">
                @if($contact->photo_url)
                    <img src="{{ $contact->photo_url }}" class="profile-picture" alt="{{ $contact->name }}" style="width: 40px;height: 40px;border-radius: 50%;object-fit: cover; margin-right: 2px;margin-top: -5px;">
                @endif
                {{ $contact->name }} 
                @if($contactType->contact_type == 'Agency Fund Holder')
                    Advisor
                @else
                    {{ $contactType->contact_type }}
                @endif
            </a>
        </li>
    </ul>
</nav>

    <style>
    /* Soft disabled look */
    .menu-disabled {
        opacity: 0.65;          /* subtle fade */
        cursor: not-allowed;
    }

    /* Slightly dim text & icons */
    .menu-disabled p,
    .menu-disabled i {
        color: #f4f6f9 !important;
    }

    /* Remove hover highlight */
    .menu-disabled:hover {
        background-color: transparent !important;
    }
    .link-status i {
        float: right;
        padding-top: 5px;
    }
    </style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 gn-main-sidebar" style="background: {{\App\Models\FaPartner::getClientBackgroundColor()}}">
    <a href="" class="brand-link" style="background: #fff">
        <img src="{{\App\Models\FaPartner::getClientHeaderLogo()}}" alt="GN" class="brand-image">
    </a>
	
    <div class="sidebar">
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent2 nav-compact nav-legacy nav--flatv-flat" data-widget="treeview" role="menu" data-accordion="false">
              
                @if(!$authorized)

                    <li class="nav-header ps-3">
                        <small>Application Steps </small>
                    </li>
                    <hr class="hr-divider">

                    @foreach ($dafLeftMenu as $item)

                        <li class="nav-item">

                            {{-- ================= Parent Menu ================= --}}
                            <a
                                class="nav-link {{ !$item['enabled'] ? 'menu-disabled' : '' }}"
                               @if($item['enabled'])
                                    href="{{ route($item['route'], $dafId) }}"
                                @else
                                    href="javascript:void(0);"
                                    aria-disabled="true"
                                @endif
                                >
                                <i class="far fa-edit"></i>
                                <p>{{ $item['name'] }}</p>

                                {{-- Status Icons --}}
                                @include( 'agency.agency-advisor.daf-registration.menu-item-status', ['status' => $item['status']] )
                            </a>

                            {{-- ================= Children Menu ================= --}}
                            @if(!empty($item['children']))
                                <ul class="nav ms-3">
                                    @foreach ($item['children'] as $child)
                                        <li class="nav-item">
                                            <a
                                                class="nav-link {{ !$item['enabled'] ? 'menu-disabled' : '' }}"
                                                @if($item['enabled'])
                                                    href="{{ route($child['route'], $dafId) }}"
                                                @else
                                                    href="javascript:void(0);"
                                                @endif
                                            >
                                                <i class="fas fa-money-check-alt"></i>
                                                <p>{{ $child['name'] }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- ================= Additional Donors ================= --}}
                            @if ($item['key'] === \App\Models\DAFAccount::DAF_ADDITIONAL_DONOR && $item['enabled'])
                                <ul class="nav ms-3">

                                    @if (!empty($additionalDonors))
                                        @foreach ($additionalDonors as $name)
                                            <li class="nav-item">
                                                <a
                                                    href="{{ route('agency-daf-additional-donor', ['key' => $name['key'], 'id' => $id]) }}"
                                                    class="nav-link"
                                                >
                                                    <i class="fas fa-user"></i>
                                                    <p>{{ $name['first_name'] }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif

                                    @if (count($additionalDonors) < $maxAdditionalDonors)
                                        <li class="nav-item">
                                            <a href="{{ route('agency-daf-additional-donor', $id) }}" class="nav-link">
                                                <i class="fa fa-user-plus"></i>
                                                <p>Add</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            {{-- ================= Successors ================= --}}
                            @if ($item['key'] === \App\Models\DAFAccount::DAF_SUCCESSORS && $item['enabled'])
                                @if(!$endowmentSelected && $endowmentInfo)
                                    <ul class="nav ms-3">
                                        <li class="nav-item">
                                            <a href="{{ route('agency-daf-successors-individuals', $id) }}" class="nav-link">
                                                <i class="fa fa-users"></i>
                                                <p>Individuals</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('agency-daf-successors-organizations', $id) }}" class="nav-link">
                                                <i class="fa fa-university"></i>
                                                <p>Charitable Organizations</p>
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            @endif
                        </li>
                        @endforeach
                @else
                    <li class="nav-header ps-3">
                        <small>Application Steps</small>
                    </li>
                    <hr class="hr-divider">
                    <li class="nav-item">
                        <a href="{{ route('agency-daf-application-status', $dafId) }}" class="nav-link">
                            <i class="far fa-edit"></i>
                            <p>Completed Application</p>
                        </a>
                    </li>
                @endif
            </ul>
            <br>
            <br>
        </nav>
    </div>
</aside>

<script>
    
    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });

    $(document).ready(function(){
        $('a:not([href]) p').css("color", "#dfdfdf");
        $('a:not([href]) i').css("color", "#dfdfdf");
        $('a:not([href])').css("cursor", 'default');
    });
</script>