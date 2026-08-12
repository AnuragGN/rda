<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>

<div style="min-width:280px; background: white;">

    <div style="text-align: center; max-width:600px; min-width:220px; margin: 30px auto;
    background: #fdfdfd; box-shadow: 0 0 15px 3px rgba(0,0,0,0.05); border: 1px solid #f9f9f9;">

        {{-- header --}}
        <div style="background: #666; color:white;">
            <p style="font-size: 1rem; padding: 0.75rem; margin: 0; text-align: left; font-weight: bold;">
                <a href="{{ \App\Models\ClientInfo::getBaseUrl() }}" style="color: #fff"> \App\Models\ClientInfo::name() </a>
            </p>
        </div>

        {{-- body --}}
        <div style="text-align: left; width: 90%; margin: 30px auto;">
            @yield('content')
        </div>

        {{-- footer --}}
        <div style="background: #666; color: #eee; padding: 0.5rem;">
            <p style="margin: 0; font-size: 0.8rem; ">
                <a href="{{  url('/') }}" style="color: #eee; padding-right: 1rem;">Home</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
