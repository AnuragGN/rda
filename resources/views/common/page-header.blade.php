<?php
// $pageTitle - Page header
// $hcType - header container
// $hcColXl - column width on XL screen
if(!isset($pageSubtitle))$pageSubtitle = null;
if (!isset($hcType)) $hcType = 'container';
if (!isset($hcXlWidth)) $hcXlWidth = '12';
if (!isset($titleInfo)) $titleInfo = null;

$splitC = null;    
if (isset($split84)) {
    $splitA = "col-md-8";
    $splitB = "col-md-4";
} else if (isset($split93)) {
    $splitA = "col-md-9";
    $splitB = "col-md-3";
} else {
    $splitA = "col-sm-6";
    $splitB = "col-sm-6";
    if (!empty($showRefresh)) {
        $splitB = 'col-sm-3';
        $splitC = 'col-sm-3'; // Refresh
    } 
}

?>


<section class="content-header">
    <div class="{{$hcType}}">
        <div class="row">
            <div class="col-xl-{{$hcXlWidth}}">
                <div class="row">
                    <div class="{{$splitA}}">
                        <h1 id="id_page_title">
                             @isset($icon)
                                <i class="{{ $icon }}" style="color:#00a9cf;"></i>
                            @endisset
                            {{$pageTitle}}
                            @if($titleInfo)
                                <sup style="font-size: 1rem;" data-toggle="tooltip" data-placement="top" title="{{$titleInfo}}">
                                    <i class="fa fa-info-circle"></i>
                                </sup>
                            @endif
                        </h1>
                    </div>
                    
                    <div class="{{$splitB}}">
                        <div class="gn-breadcrumbs">
                            @include('common.breadcrumbs')
                        </div>
                    </div>
                     {{-- Refresh --}}
                    @if(!empty($showRefresh))
                        <div class="{{ $splitC }} text-end">
                            <button class="btn btn-outline-primary btn-sm" style="margin-top:8px;float:right;"
                                    onclick="window.location.reload();">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if($pageSubtitle)
                <div class="col-12">
                    {{$pageSubtitle}}
                </div>
            @endif
        </div>
    </div>

</section>

