<?php
if(!isset($fullWidthFooter)) $fullWidthFooter = false;
//$footerContainer = $fullWidthFooter ? 'container' : 'container-fluid';
$footerContainer = 'container';

$footer = \App\Models\FaPartner::getFooterBranding();
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
                 <a href="https://giftingnetwork.com/"><img class="logo" src="{{ $footer['logo'] }}" alt=""></a>
             </div>
             <div class="col-md-5 llr">
                 <div class="contact-details">
                     <p style="white-space: pre-line">  {{ $footer['address'] }} </p>
                     <p> {{ $footer['phone'] }} </p>
                     <p> {{ $footer['email'] }} </p>
                 </div>
                 <div class="copy-right">
                     © {{ date('Y') }} GiftingNetwork LLC. All Rights Reserved.
                 </div>
             </div>

             <div class="col-md-4">
                 <div class="d-none d-md-block fw600">QUICK LINKS</div>
                 <ul class="gna-ul">
                     {{--<li> <a href="https://giftingnetwork.com/">HOME</a></li>--}}
                     {{--<li> <a href="https://giftingnetwork.com/giftingnet/">GIFTINGNET</a></li>--}}
                     {{--<li> <a href="https://giftingnetwork.com/request-a-demo/">REQUEST DEMO</a></li> --}}
                     {{--<li> <a href="https://giftingnetwork.com/about-us/">ABOUT</a></li>--}}
                     {{--<li> <a href="https://giftingnetwork.com/contact/">CONTACT</a></li>--}}
					 
					@foreach($footer['quick_links'] as $link)
						<li>
							<a href="{{ $link['url'] }}" target="_blank">{{ strtoupper($link['name']) }}</a>
						</li>
					@endforeach
                 </ul>
            </div>
        </div>
    </footer>

    @include('agency.layouts.power-by-footer')
</div>


