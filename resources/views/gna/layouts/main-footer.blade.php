<?php
if(!isset($fullWidthFooter)) $fullWidthFooter = false;
//$footerContainer = $fullWidthFooter ? 'container' : 'container-fluid';
$footerContainer = 'container';
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
                 <a href="https://giftingnetwork.com/"><img class="logo" src="{{\App\Models\FaPartner::getClientFooterLogo()}}" alt=""></a>
             </div>
             <div class="col-md-5 llr">
                 <div class="contact-details">
                     <p> 89 HEADQUARTERS PLAZA, SUITE 1446, <br> MORRISTOWN, NEW JERSEY, 07960 </p>
                     <p> 973.984.8200 </p>
                     <p> INFO@GIFTINGNETWORK.COM </p>
                 </div>
                 <div class="copy-right">
                     © 2022 GiftingNetwork LLC. All Rights Reserved.
                 </div>
             </div>

             <div class="col-md-4">
                 <div class="d-none d-md-block fw600">QUICK LINKS</div>
                 <ul class="gna-ul">
                     <li> <a href="https://giftingnetwork.com/">HOME</a></li>
                     {{--<li> <a href="https://giftingnetwork.com/giftingnet/">GIFTINGNET</a></li>--}}
                     <li> <a href="https://giftingnetwork.com/request-a-demo/">REQUEST DEMO</a></li>
                     <li> <a href="https://giftingnetwork.com/about-us/">ABOUT</a></li>
                     <li> <a href="https://giftingnetwork.com/contact/">CONTACT</a></li>
                 </ul>
            </div>
        </div>
    </footer>

    @include('donor.layouts.power-by-footer')
</div>


