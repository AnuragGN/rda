<?php
if(!isset($fullWidthFooter)) $fullWidthFooter = false;
$footerContainer = $fullWidthFooter ? 'container' : 'container-fluid';

$clientBaseUrl = \App\Models\ClientInfo::getBaseUrl();
?>
@if($fullWidthFooter)
    <style>
        .main-footer { margin: 0!important; }
    </style>
@endif

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="info-modal-content">
            <div class="modal-body" id="info-modal-body">
            </div>
        </div>
    </div>
</div>

{{--<div class="gn-footer">--}}
    {{--<div id="footer">--}}
<div class="gn-footer main-footer">
    <div id="footer">
        <footer class={{$footerContainer}}>
        <div class="row">
            <div class="footer-left col-md-3">
                <p class="address">
                    <span>Greater Milwaukee Foundation</span><br>
                    101 W Pleasant St., Ste 210<br>
                    Milwaukee, WI 53212<br>
                    tel. 414-272-5805<br>
                    fax. 414-272-6235
                </p>
                <ul>
                    <li><a href="https://www.facebook.com/GreaterMilwaukeeFoundation" target="_blank" class="fb">Facebook</a></li>
                    <li><a href="http://twitter.com/#!/GrMKEFdn" target="_blank" class="twitter">Twitter</a></li>
                </ul>
            </div>
            <div class="footer-right col-md-9 d-none d-md-block">
                <div class="row">
                <div class="col-sm-3" id="fsm">
                    <ul>
                        <li><a href={{ $clientBaseUrl . "donors/"}}><span>Donors</span></a></li>
                        <li><a href={{ $clientBaseUrl . "donors/become-a-donor/"}}>Become a Donor</a></li>
                        <li><a href={{ $clientBaseUrl . "donors/current-donors/"}}>Current Donors</a></li>
                        <li><a href={{ $clientBaseUrl . "donors/foundation-funds/"}}>Foundation Funds</a></li>
                        <li><a href={{ $clientBaseUrl . "donors/investment-information/"}}>Investment Information</a></li>
                        <li><a href={{ $clientBaseUrl . "donors/donor-stories/"}}>Donor Stories</a></li>
                        <li><a href={{ $clientBaseUrl . "donors/give-online/"}}>Give Online</a></li>
                    </ul>
                    <ul>
                        <li><a href={{ $clientBaseUrl . "grants/"}}><span>Grants</span></a></li>
                        <li><a href={{ $clientBaseUrl . "grants/recent-grants/"}}>Recent Grants</a></li>
                        <li><a href={{ $clientBaseUrl . "grants/grants-at-work/"}}>Grants at Work</a></li>
                        <li><a href={{ $clientBaseUrl . "grants/grant-seekers/"}}>Grant Seekers</a></li>
                    </ul>
                </div>
                <div class="col-sm-3" id="fsm">
                    <ul>
                        <li><a href={{ $clientBaseUrl . "professional-advisers/"}}><span>Professional Advisers</span></a></li>
                        <li><a href={{ $clientBaseUrl . "professional-advisers/benefits-to-your-clients/"}}>Benefits to Your Clients</a></li>
                        <li><a href={{ $clientBaseUrl . "professional-advisers/professional-adviser-resources/"}}>Professional Adviser Resources</a></li>
                        <li><a href={{ $clientBaseUrl . "professional-advisers/herbert-j-muller-society/"}}>Herbert J. Mueller Society</a></li>
                        <li><a href={{ $clientBaseUrl . "professional-advisers/adviser-faqs/"}}>Adviser FAQs</a></li>
                    </ul>
                    <ul>
                        <li><a href={{ $clientBaseUrl . "newsroom/"}}><span>News</span></a></li>
                        <li><a href={{ $clientBaseUrl . "newsroom/recent-news/"}}>Recent News</a></li>
                        <li><a href={{ $clientBaseUrl . "newsroom/news-archive/"}}>News Archive</a></li>
                        <li><a href={{ $clientBaseUrl . "newsroom/media-kit/"}}>Media Kit</a></li>
                    </ul>
                </div>
                <div class="col-sm-3" id="fsm">
                    <ul>
                        <li><a href={{ $clientBaseUrl . "community-leadership/"}}><span>Community Leadership</span></a></li>
                        <li><a href={{ $clientBaseUrl . "community-leadership/thriving-communities/"}}>Thriving Communities</a></li>
                        <li><a href={{ $clientBaseUrl . "community-leadership/connected-people/"}}>Connected People</a></li>
                        <li><a href={{ $clientBaseUrl . "community-leadership/regional-vitality/"}}>Responsive Grantmaking</a></li>
                        <li><a href={{ $clientBaseUrl . "community-leadership/impact-investing/"}}>Impact Investing</a></li>
                        <li><a href={{ $clientBaseUrl . "community-leadership/civic-engagement/"}}>Civic Engagement</a></li>
                    </ul>
                </div>
                <div class="col-sm-3" id="fsm">
                    <ul>
                        <li><a href={{ $clientBaseUrl . "about-us/"}}><span>About Us</span></a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/our-mission/"}}>Our Mission, Vision and Values</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/history-of-foundation/"}}>History of Foundation</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/board/"}}>Board of Directors</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/staff/"}}>Staff</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/financial/"}}>Financial Information</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/annual-report/"}}>Annual Report</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/awards/"}}>Awards</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/counties-we-serve/"}}>Counties We Serve</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/careers/"}}>Careers</a></li>
                        <li><a href={{ $clientBaseUrl . "about-us/stay-connected/"}}>Stay Connected</a></li>
                    </ul>
                </div>
                </div>
            </div><!--end 9 -->
        </div>
        <div class="row">
            <div class="col-lg-7">
                <a href="http://www.cfstandards.org/" target="_blank" class="cfns">Confirmed in compliance with National Standards for U.S. Community Foundations</a>
            </div>
            <div class="col-lg-5">
                <div class="copyright">© 2022 Greater Milwaukee Foundation <a href={{ $clientBaseUrl . "site-map"}}>Site Map</a> | <a href={{ $clientBaseUrl . "privacy"}}>Privacy Statement</a></div>
            </div>
        </div>
    </footer>
    @include('donor.layouts.power-by-footer')
    </div>
</div>

@if(false)
    <footer class="footer-oye darkbg">
        <div class="container">
        <span class="d-flex justify-content-between">
            <span>All Rights Reserved</span>

            <ul class="footer-links">
                <li><a href="#">About</a></li>
                <li> &middot; </li>
                <li><a href="#">Terms</a></li>
                <li> &middot; </li>
                <li><a href="#">Policy</a></li>
                <li> &middot; </li>
                <li><a href="#">Contact Us</a></li>
                <li> &middot; </li>
                <li><a href="#">Feedback</a></li>
            </ul>

            <span>&nbsp;</span>
        </span>
        </div>
    </footer>
@endif
