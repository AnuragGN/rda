<style>
    #theLogoutForm {margin: 0;}
</style>
<form name="logoutForm" method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Confirm Log Out');">
    {{ csrf_field() }}
    <a class="{{ $class ?? 'btn btn-danger' }}" style="margin-bottom: 2px; {{ $style ?? '' }}"
        href="javascript:document.logoutForm.submit()">Log Out</A>
</form>

{{--<a  class="dropdown-item"--}}
    {{--onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>--}}
{{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
    {{--{{ csrf_field() }}--}}
{{--</form>--}}


