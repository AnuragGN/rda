@extends ('donor.layouts.main')

@section ('content')

    <br><br><br><br>
    <div class="row text-center">

        <div style="margin: 0 auto; min-width: 300px; max-width: 500px; padding: 15px; background: #f9f9f9; border: 1px solid #eee">
            @if (!$error)
                <h3> Check your email</h3>
                <p>An email has been sent to your registered email address - {{ $email }}. To activate your account,
                    open the link contained in the email.
            @else
                <h3> Ops! Bad Link</h3>
                <p>The request could not be processed.
            @endif
        </div>
    </div>

    <br>
    <div class="row">
        <a href="/" style="margin: 0 auto; font-size: 2rem">Home</a>
    </div>

@endsection
