@extends ('donor.layouts.main')

@section ('content')

    <br><br><br><br>
    <div class="row text-center">

        <div style="margin: 0 auto; max-width: 500px; padding: 15px; background: #f9f9f9; border: 1px solid #eee">

            <h3> Account Not Active</h3>

            <p>An email was sent to your registered email address - {{ $email }}. To activate your account,
                open the link contained in the email.

        </div>
    </div>

    <br>
    <div class="row">
        <a href="/" style="margin: 0 auto; font-size: 2rem">Home</a>
    </div>


    <br>
    <div class="row">
        <a href="{{ route('activation-link', ['id' => $id, 'email' => $email]) }}" style="margin: 0 auto; font-size: 1rem">Resend account activation link</a>
    </div>

@endsection