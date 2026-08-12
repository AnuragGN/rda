<h1 class="page-title">
    GiftingNetwork
</h1>
<p>GiftingNetwork transforms philanthropy by empowering nonprofit organizations with world class technology.</p>
<p>GiftingNetwork’s vision is a world where nonprofit organizations compete equally with commercial DAFs by providing a Wall Street-caliber fund and trust accounting platform catering to nonprofits’ tracking and reporting needs.</p>

@if(\App\Models\ClientConfig::feature('PUBLIC_DONATIONS'))
    <a href="{{ route('donation.create') }}" class="btn btn-theme btn-sm btn-donate">Donate Now</a>
@endif

{{--@include('donation._form_donation')--}}

{{--@include('donation.view', compact('model'))--}}
