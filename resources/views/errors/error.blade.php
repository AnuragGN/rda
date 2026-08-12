@extends ('layouts.main', ['errorPage' => true])

<style>
    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .error-page {
        margin: 3rem 0 1rem;
        min-height: 30vh;
    }
    .error-page .icon {
        font-size: 30px;
        margin: 4rem 0 0.5rem;
    }
    .error-page .message {
        font-size: 18px;
        margin-bottom: 2rem;
        font-weight: 600;    }
</style>

@section ('content')

    <div class="flex-center text-center error-page">

        @if(\App\Models\ClientInfo::isHGA())
            <div class="message">
                <br>
                Your request could not be processed at this time.
                Please email us at <a href="mailto:dafs@highgroundadvisors.org">dafs@highgroundadvisors.org</a> for support.
            </div>
        @else
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="message">{{ $message }} ({{ $code }})</div>
        @endif

        <div>
            <a href=" {{ url('/') }}"><button class="rounded-lg hide">Go Home</button></a>
            <a href="/" class="btn btn-theme btn-sm btn-wide">Home</a>
            <br>
            <br>
            <span class="font-small">@include('utils.logout', ['class' => ''])</span>
        </div>
    </div>

@endsection
