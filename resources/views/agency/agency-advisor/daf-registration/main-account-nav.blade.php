<header>

    <nav class="navbar navbar-light navbar-dark2 navbar-expand-md gn-navbar jcf-navbar mb-0">
        <div class="container ml-0x">
            <a class="navbar-brand" href="/">
                <i class="fab fa-bandcamp hide"></i>
                <img src="{{\App\Models\ClientInfo::logo()}}" class="d-inline-block align-top gn-logo" alt="">
            </a>
            <div class="nav-login">
                @if(\App\Models\ClientInfo::isHGA())
                    <span class="ml-2">CALL OUR OFFICE 800.747.5564</span>
                @endif
                <a class="btn btn-theme btn-sm ml-3" href="{{route('login')}}">Log In</a>
            </div>
        </div>
    </nav>

    <div id="nav-powered" style="right: 90px;">
        <a class="nav-powered-view" href="//giftingnetwork.com/" target="_blank"><img src="/ma/images/logo_xs.png">Powered by GiftingNetwork</a>
        <br/>
    </div>

</header>

<script>
    $(function(){ poweredBy(); });

    function poweredBy(){
        var widthX = $(window).width();
        var width = window.innerWidth;
        var margin = 0;
        if ( width >= 1200) { // 1140px
            // console.log("A: 20");
            margin = 20 + (width - 1140) / 2;
        } else if (width >= 992) { // 960
            // console.log("B: 20");
            margin = 20 + (width - 960) / 2;
        } else if (width >= 768) { // 720
            // console.log("C: 20");
            margin = 20 + (width - 720) / 2;
        } else if (width >= 576) { // 540
            // console.log("D: 0");
            margin = (width - 540) / 2;
        } else { // 540
            // console.log("E: 24");
            margin = 24;
        }
        $('#nav-powered').css('right', margin);
        console.log("widthX:"  + widthX );
        console.log("width:"  + width );
        console.log("margin:"  + margin );
    }
</script>
