@extends ('donor.layouts.main')

@section ('content')

    <br><br><br><br>
    <div class="row text-center">

        <div style="margin: 0 auto; min-width: 300px; max-width: 500px; padding: 15px; background: #f9f9f9; border: 1px solid #eee">
            <h3> Page Not Found! </h3>

            <p> The page could not be found on server. Please check the URL.
        </div>
    </div>

    <br>
    <div class="row">
        <div style="margin: 0 auto;">
            <a href="/" style="font-size: 2rem; margin-right: 2rem;">Home</a>
            @if(Auth::guest())
                <a href="#"
                   style="font-size: 2rem"
                   data-toggle="modal"
                   data-name="Login"
                   data-target="#authModal">
                    Sign in</a>
            @endif
        </div>
    </div>

@endsection
