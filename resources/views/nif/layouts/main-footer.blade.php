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
             <div class="col-md-4 llr">
                 <a href="https://nif.org/"><img class="logo" src="/ma/images/nif/logo-gray.png" alt=""></a>
             </div>
             <div class="col-md-3 llr">
                 <div class="contact-details">
                     For wire instructions or stock transfer instructions, please follow <a href="https://www.nif.org/get-involved/ways-to-give/why-make-a-gift-of-stock/" style="text-decoration: underline">this link</a> or contact Andrew Goldblatt at <a href="mailto:andrewg@nif.org">andrewg@nif.org</a>
                 </div>
             </div>
             <div class="offset-md-1 col-md-3 llr">
                 <div class="contact-details">
                     <p>For any other information, please contact Jennifer Spitzer, Vice President of Finance, Operations & Administration, at 415-543-5055, or <a href="mailto:jennifer@nif.org">jennifer@nif.org</a>.</p>
                     <ul class="social-icons">
                         <li><a href="https://www.facebook.com/NewIsraelFund"><i class="fab fa-facebook"></i></a></li>
                         <li><a href="https://twitter.com/NewIsraelFund"><i class="fab fa-twitter"></i></a></li>
                         <li><a href="https://www.instagram.com/NewIsraelFund"><i class="fab fa-instagram"></i></a></li>
                         <li><a href="https://www.nif.org/"><img src="/ma/images/nif/nif-icon.png"></a></li>
                     </ul>
                 </div>
             </div>
         </div>
    </footer>

    @include('donor.layouts.power-by-footer')
</div>
