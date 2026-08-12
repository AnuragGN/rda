<?php
/** @var \App\Models\OrganizationInfo $org */
$info = $org->getOrgInfo();
$story = $org->getStory();

$links = Share::page(Request::fullUrl(), 'Learn more about ' . $info->name . ' at ')
        ->facebook()
        ->twitter()
        ->linkedin('')
        ->whatsapp()
        ->getRawLinks();
// ->onlyLink();
?>

@extends ('donor.layouts.main')


@section('meta-title', $info->name)

@if($story->mission)
    @section('meta-description', $org->getShortText($story->mission))
@endif
@if($org->image)
    @section('meta-image', url($org->image))
@endif

@section ('content')

    <script src="{{ asset('js/share.js') }}"></script>

    @include('donor.common.req-info-modal', ['model' => $org])

    @include('common.page-header', ['pageTitle' => 'Organization'])

    <section class="content">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-r-15 org-view">

                    @if($custom->feature->SOCIAL_SHARE_ORG)
                        <div id="social-links" style="display: inline-block;">
                            <ul style=" display: inline;">
                                <li>
                                    <a href="{{$links['facebook']}}" class="social-button " id=""
                                       data-toggle="tooltip" title="Share on Facebook">
                                        <span class="fab fa-facebook-square"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{$links['twitter']}}" class="social-button " id=""
                                       data-toggle="tooltip" title="Share on Twitter">
                                        <span class="fab fa-twitter"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{$links['linkedin']}}" class="social-button " id=""
                                       data-toggle="tooltip" title="Share on LinkedIn">
                                        <span class="fab fa-linkedin"></span>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="{{$links['whatsapp']}}" class="social-button" id=""
                                       data-toggle="tooltip" title="Share on WhatsApp">
                                        <span class="fab fa-whatsapp"></span>
                                    </a>
                                </li>

                                <li>
                                    <a target="_blank" href="{{ route('print-organization', ['id' => $org->organization_id]) }}"
                                       class="social-button"
                                       data-toggle="tooltip" title="Print Organization Profile">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    @endif

                    @include('org.organization')
                    {{--@include('org.organization-info')--}}

                    <br/>

                </div>

            </div>
        </div>

    </section>

@endsection

