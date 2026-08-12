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
                <a href="https://www.mercy.com/"><img class="logo" src="/ma/images/mercy/logo-white.svg" alt=""></a>
            </div>
            <div class="col-md-3 llr">
                <div class="contact-details">
                </div>
            </div>
            <div class="offset-md-1 col-md-3 llr">
                <div class="contact-details">
                    <p></p>
                    <ul class="social-icons">
                        <li><a href="https://www.facebook.com/LivingMercyHealth"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/mercy_health"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://instagram.com/mercy_health/"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    @include('donor.layouts.power-by-footer')
</div>
