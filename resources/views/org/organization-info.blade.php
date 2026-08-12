<?php
/** @var \App\Models\OrganizationInfo $org */
/** @var \App\Models\OrganizationInfo $info */
$info = $org->getOrgInfo();
$story = $org->getStory();
?>

<div class="row">
    <div class="col-12">
        <div class="page-subtitle mt-2">
            <h2>{{$info->name}}</h2>

            @if(!$custom->feature->SOCIAL_SHARE_ORG)
                <a target="_blank" href="{{ route('print-organization', ['id' => $org->organization_id]) }}"
                   class="btn btn-sm btn-light"
                   data-toggle="tooltip" title="Print Organization Profile">
                    Print <i class="fas fa-print"></i>
                </a>
            @endif

        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 image-header">
        <img class="gn-shadow" src="{{$org->image}}" />
        <p class="address">{!! $org->getInfoAddressMultiLine() !!}

            @if (!empty($org->getWebsite()))
                <br>
                <span style="display: block; padding: 4px 0;">Website: <a target="_blank" href="{{$org->getWebLink()}}" style="font-weight: normal">{{$org->getWebsite()}}</a></span>
            @endif

        </p>
    </div>

    @if(\App\Helpers\GnUtils::isDonorSession())
        <div class="col-12">
            <div class="mag-link" style="line-height: 12px;">
                @if(\App\Models\ClientInfo::isJCF() || \App\Models\ClientInfo::isGNA() || \App\Models\ClientInfo::isGMF())
                    <a href="javascript:void(0);" data-org-id="{{$org->getId()}}}"
                       onclick="jsReqInfoForm.onShowMoreInfo()"
                       class="btn btn-light shadowed btn-sm">Request More Info</a>
                @endif
                <a href="{{ $org->getMakeGrantUrl() }}" class="btn btn-theme btn-sm ml-2">{{ $custom->text->MAKE_A_GRANT }}</a>
            </div>
        </div>
    @endif
</div>

@if($story->mission)
    <div class="row">
        <div class="col-12">
            <h4 class="page-subtitle">Mission</h4>
        </div>
    </div>
    <p>{!! $story->mission !!}</p>
@endif

@if($story->history)
    <div class="row">
        <div class="col-12">
            <h4 class="page-subtitle">History</h4>
        </div>
    </div>
    <p>{!! $story->history !!}</p>
@endif

@if($story->programs)
    <div class="row">
        <div class="col-12">
            <h4 class="page-subtitle">Programs</h4>
        </div>
    </div>
    <p>{!! $story->programs !!}</p>
@endif

@if($story->volunteerism)
    <div class="row">
        <div class="col-12">
            <h4 class="page-subtitle">Volunteerism</h4>
        </div>
    </div>
    <p>{!! $story->volunteerism !!}</p>
@endif
