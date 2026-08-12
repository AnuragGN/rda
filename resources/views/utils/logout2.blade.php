
<form name="logoutForm2" method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Confirm Logout');">
    {{ csrf_field() }}
    <a class="{{ $class ?? 'btn btn-danger' }}" style="border-radius: 20px; margin-bottom: 2px; {{ $style ?? '' }}"
        href="javascript:document.logoutForm2.submit()">Logout</A>
</form>

{{--<a  class="dropdown-item"--}}
    {{--onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>--}}
{{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
    {{--{{ csrf_field() }}--}}
{{--</form>--}}


