<?php ?>

<header style="background: #fff; padding-bottom: 1rem;">

    <!-- Top Navigation-->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hga-nav">
                    <div class="hn-left">
                        <a href="/registration/registration"><img src="/images/demo/download.png" alt=""></a>
                    </div>
                    <div class="hn-right">
                        <div class="text-right">
                            <div id="nav-powered">
                                <a class="nav-powered-view" href="//giftingnetwork.com/" target="_blank"><img src="/images/gn-logo-sm.png">Powered by GiftingNetwork</a>
                            </div>
                        </div>
                        @if(isset($loggedInRequired) and $loggedInRequired)
                            <ul class="">
                                <li><a href="javascript:void(0);" class="">CONTACT US</a></li>
                                <li class="call hide">CALL OUR OFFICE 800.747.5564</li>
                                <li>
                                    <a href="javascript:void(0);" class="btn btn-hga-md btn-theme cta-logout">
                                        <span id="id-logout">LOGIN</span>
                                    </a>
                                </li>
                            </ul>
                        @else
                            <ul class="">
                                <li><a href="javascript:void(0);" class="">CONTACT US</a></li>
                                <li class="call hide">CALL OUR OFFICE 800.747.5564</li>
                                <li>
                                    <a href="javascript:void(0);" class="btn btn-info btn-hga-md btn-theme cta-logout">
                                        <span id="id-logout">LOGOUT</span>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</header>
