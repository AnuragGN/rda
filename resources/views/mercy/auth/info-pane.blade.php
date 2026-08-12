<h1 class="page-title">
    Mercy Health
</h1>

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin aliquam mi nec eros ultrices, eu viverra magna scelerisque. Cras gravida turpis sapien, ut viverra nulla pretium non. </p>
<p>Quisque venenatis condimentum eros. Curabitur nec euismod lorem, ut laoreet nibh. Donec fringilla eget eros tempus vulputate. Ut et gravida leo. Sed fermentum dui non laoreet dignissim. </p>

@if(\App\Models\ClientConfig::feature('PUBLIC_DONATIONS'))
    <a href="{{ route('donation.create') }}" class="btn btn-theme btn-sm btn-donate">Donate Now</a>
@endif
