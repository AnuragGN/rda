<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>

<div style="min-width:280px; background: white; font-family: sans-serif; color: #424242; line-height: 1.5; font-size: 16px;">

    <div style="max-width:600px; min-width:220px; padding: 1rem; margin: 0 0 1rem;">

        {{-- header --}}
        @include('fig.emails.header')

        {{-- body --}}
        <div style="text-align: left; width: 99%;">
            @yield('content')
        </div>

        {{-- footer --}}
        @include('fig.emails.footer')

    </div>
</div>

</body>
</html>
