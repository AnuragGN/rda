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


<style>
</style>

<div class="gn-footer main-footer">
    <footer class="{{$footerContainer}}">
        <div class="row info">

            <div class="col-lg-4 llr fcol-one">

                <div class="logo-one">
                    <a target="_blank" href="https://www.jvalley.org">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/02/jsv-logo-white.png" alt="Jewish Silicon Valley">
                    </a>
                </div>

                <div class="logo-two">
                    <a target="_blank" href="https://apjcc.org/">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/02/JCC-Los-Gatos.png" alt="JCC Los Gatos">
                    </a>

                    <a target="_blank" href="https://campshalomjcc.org/">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/02/Camp-Shalom.png" alt="Camp Shalom">
                    </a>
                </div>

                <div class="logo-three">
                    <a target="_blank" href="https://www.jvalley.org/community-relations/jewish-community-relations-council/">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/01/JCRC_Logo_Horizontal_Standalone_Horizontal_White-01-e1617397576479.png"
                             alt="JCRC Jewish Community Relations Council">
                    </a>
                </div>

            </div>

            <div class="col-lg-4 llr pt-4">

                <div class="contact-details">

                    <p>Jewish Silicon Valley<br>
                        14855 Oka Road, Los Gatos, CA 95032<br>
                        408.358.3636<br>
                        info@jvalley.org
                    </p>

                    <p>Nonprofit tax ID number 94-2222989</p>

                    <a target="_blank" href="https://www.jvalley.org/employment/">CAREER OPPORTUNITIES</a><br>

                    <a target="_blank" href="http://jvalley.org/portal">PROGRAMMING PORTAL</a><br>

                    <ul class="social-icons pt-3 pb-3">
                        <li><a target="_blank" href="https://www.facebook.com/jewishsiliconvalley/"><i class="fab fa-facebook"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/jewishsiliconvalley/"><i class="fab fa-instagram"></i></a></li>
                        <li><a target="_blank" href="https://www.youtube.com/channel/UCWs_tFeYD63SgyqIdfNo59A"><i class="fab fa-youtube"></i></a></li>
                    </ul>

                </div>
            </div>

            <div class="col-lg-4 fcol-three pt-4">

                <div class="logo-one">
                    <a target="_blank" href="https://koret.org/">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/02/Koret.png" class="attachment-full size-full" alt="Koret Foundation" loading="lazy">								</a>

                    <div style="padding-left: 1rem">Jewish Silicon Valley is proud to be a part of the Koret Initiative on Jewish Peoplehood.</div>
                </div>

                <div class="logo-two">
                    <a target="_blank" href="https://www.taubephilanthropies.org/">
                    <img src="https://www.jvalley.org/wp-content/uploads/2021/02/Taube.png" class="attachment-full size-full" alt="Taube Philanthropies" loading="lazy">								</a>
                </div>

                <div class="logo-three">
                    <a target="_blank" href="https://www.guidestar.org/profile/94-2222989">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/01/candid-seal-gold-2022-bw.png"
                             alt="Candid Gold Seal 2022">
                    </a>
                    <a target="_blank" href="https://www.charitynavigator.org/ein/942222989">
                        <img src="https://www.jvalley.org/wp-content/uploads/2021/02/Charity-Navigator.png" class="attachment-full size-full" alt="Charity Navigator Four Star Charity" loading="lazy">
                    </a>
                </div>

            </div>
        </div>
    </footer>

    @include('donor.layouts.power-by-footer')
</div>

