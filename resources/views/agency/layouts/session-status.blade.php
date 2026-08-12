<div id="id-flash-message" class="alert alert-info flash-position hide"></div>

@if (session('status'))
    <div id="id-session-status" class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('message'))
    <div id="id-session-message" class="alert alert-info">
        {{ session('message') }}
    </div>
@endif

@if (session('error'))
    <div id="id-session-message" class="alert alert-info">
        {{ session('error') }}
    </div>
@endif
