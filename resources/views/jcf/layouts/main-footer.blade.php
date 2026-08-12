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
            <div class="col-6 text-left">
                © 2022 All Rights Reserved<br>
                <a href="https://jcfsandiego.org" target="_blank">Jewish Community Foundation San Diego</a>, CA<br>
                <a href="https://jcfsandiego.org/legal" target="_blank">Legal</a> | Tax ID 95-2504044
            </div>

            <div class="col-6 text-right">
                <a target="_blank" href="http://goo.gl/maps/21ZL">Joseph and Lenka Finci Jewish Community Building<br>4950 Murphy Canyon Road, San Diego, CA 92123</a><br>Phone: 858-279-2740 | Fax: 858-279-6105
            </div>
        </div>
    </footer>
    @include('donor.layouts.power-by-footer')
</div>

