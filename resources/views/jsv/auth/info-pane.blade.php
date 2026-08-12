<h1 class="page-title">
    Jewish Silicon Valley
</h1>

<p>Jewish Silicon Valley’s mission (JSV) is to harness the power of community to improve lives, build bridges of understanding and strengthen the Jewish people here, in Israel and around the world.</p>
<p>Our vision is to ensure a vibrant Jewish community in Silicon Valley and promote the well-being of all people by providing visionary leadership, philanthropic support, meaningful programs and experiences that are rooted in Jewish values and traditions.</p>

@if(\App\Models\ClientConfig::feature('PUBLIC_DONATIONS'))
    <a href="{{ route('donation.create') }}" class="btn btn-theme btn-sm btn-donate">Donate Now</a>
@endif

{{--@include('donation._form_donation')--}}

{{--@include('donation.view', compact('model'))--}}
