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
    .main-footer .social {
        border-left: 2px solid #dfdfdc;
        padding: 2rem 1rem;
    }
    .main-footer .social a {
        display: inline-block;
        margin: 1rem;
        opacity: 1;
        transition: opacity 0.3s;
    }
    .main-footer .gna-ul a {
        font-size: 14px;
        color: #7f7f7c;
        text-transform: uppercase;
    }
</style>
<div class="gn-footer main-footer">
    <footer class="{{$footerContainer}}">
         <div class="row info mt-2">
             <div class="col-md-3">
                 <a href="/"><img class="logo" src="/ma/images/ffp/fv-logo-dark.svg" alt=""></a>
             </div>
             <div class="col-md-2">
                 <ul class="gna-ul">
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/">Home</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/why-focus/">Why Focus?</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/partner-firms/">Partner Firms</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/client-solutions/">Client Solutions</a></li>
                 </ul>
            </div>
             <div class="col-md-2">
                 <ul class="gna-ul">
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/about/">About</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/contact/">Contact</a></li>
                     <li><a target="_blank" rel="noopener" href="http://ir.focusfinancialpartners.com/news-releases">News</a></li>
                     <li><a target="_blank" rel="noopener" href="https://ir.focusfinancialpartners.com/">Investor Relations</a></li>
                 </ul>
             </div>
             <div class="col-md-2">
                 <ul class="gna-ul">
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/careers/">Careers</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/disclosure/">Disclosure</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/wp-content/uploads/2020/03/Terms-of-Use-2-26-20-00052128-7.pdf" aria-label="Terms of Use - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://focusfinancialpartners.com/wp-content/uploads/2020/03/terms-of-use-2-26-20-00052128-7.pdf$termsofuse">Terms of Use</a></li>
                     <li><a target="_blank" rel="noopener" href="https://focusfinancialpartners.com/wp-content/uploads/2020/03/Focus-Privacy-Policy-3-20-2020-00051909-13.pdf" aria-label="Privacy Policy - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://focusfinancialpartners.com/wp-content/uploads/2020/03/focus-privacy-policy-3-20-2020-00051909-13.pdf$privacypolicy">Privacy Policy</a></li>
                 </ul>
             </div>

             <div class="col-md-3">
                 <div class="social">
                     <a target="_blank" rel="noopener" href="https://www.linkedin.com/company/focus-financial-partners/">
                         <img src="https://focusfinancialpartners.com/wp-content/uploads/2019/02/linkedin-icon.svg"
                              alt="" role="presentation" width="40">
                     </a>
                 </div>
             </div>
         </div>
        <div class="copy-right text-center mb-2">
            Copyright ©2023 Focus Financial Partners, All rights reserved.
        </div>
    </footer>

    @include('donor.layouts.power-by-footer')
</div>


