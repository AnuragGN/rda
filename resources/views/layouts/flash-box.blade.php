
<div class="flash-message">
    @foreach (['error', 'danger', 'warning', 'success', 'info'] as $msg)
        @if(\Illuminate\Support\Facades\Session::has($msg))
            <div id="flashbox-{{$msg}}" class="flash-box">
                <div class="alert alert-{{ $msg }}">
                    {{ \Illuminate\Support\Facades\Session::get($msg) }}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            </div>
        @endif
    @endforeach
</div>