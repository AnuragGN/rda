<?php
if(!isset($fullWidthFooter)) $fullWidthFooter = false;
$footerContainer = $fullWidthFooter ? 'container' : 'container-fluid';
?>
@if($fullWidthFooter)
    <style>
        .main-footer { margin: 0!important; }
    </style>
@endif

<div class="gn-footer main-footer">
    <footer class="{{$footerContainer}}">
        <div class="row info">
            <div class="col-md-3 llr">
                <img class="logo" src="/ma/images/hga/logo-footer.png" alt="">
            </div>
            <div class="col-3 llr">
                <ul class="hga-ul">
                    <li><a href="https://www.highgroundadvisors.org/images/HGA_DAF_ProgramGuide.pdf#page=1" target="_blank">HIGHGROUND DAF PROGRAM GUIDE</a></li>
                    <li><a href="https://www.highgroundadvisors.org/faqs#DAFs" target="_blank">FAQs</a></li>
                </ul>
            </div>
            <div class="col-md-3 llr">
                <p>1717 MAIN STREET, SUITE 1400 <br>DALLAS, TEXAS 75201-4622</p>
                <p>PHONE: 214.978.3300 | 800.747.5564 <br>FAX: 214.978.3397</p>
            </div>
            <div class="col-md-3 social-icon">
                <a href="https://www.facebook.com/highgroundadvisors" target="_blank"> <img class="fb" src="/ma/images/hga/facebook.png"></a>
                <a href="https://www.instagram.com/highgroundadvisors/" target="_blank"><img class="ins" src="/ma/images/hga/instagram.png"></a>
                <a href="https://twitter.com/highgroundadv" target="_blank"><img class="twi" src="/ma/images/hga/twitter.png"></a>
                <a href="https://www.linkedin.com/company/highground-advisors" target="_blank"><img class="ldn" src="/ma/images/hga/linkedin.png"></a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="hga-copyright">© 2023 HighGround Advisors. All Rights Reserved.</div>
            </div>
        </div>
    </footer>
    @include('donor.layouts.power-by-footer')
</div>
