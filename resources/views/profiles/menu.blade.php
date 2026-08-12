
<div class="container-fluid profile-nav">
    <div class="container">
        <div class="row">
            <nav class="col-12" id="id_tabs_profile">
                <ul>
                    <li><a href="{{route('profile')}}">Profile</a></li>
                    <li><a href="{{route('change-password-form')}}">Change Password</a></li>
                    <li style="display: none">
                        Interest Profile
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    $(function(){
        highlighttabs("#id_tabs_profile", "#aa");
    });
</script>
