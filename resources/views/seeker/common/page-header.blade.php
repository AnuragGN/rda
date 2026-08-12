<?php
// $hcType - header container
// $hcColXl - column width on XL screen

if (!isset($hcType)) $hcType = 'container';
if (!isset($hcXlWidth)) $hcXlWidth = '12';
?>

<section class="content-header">
    <div class="{{$hcType}}">
        <div class="row">
            <div class="col-xl-{{$hcXlWidth}}">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>{{$pageTitle}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="gn-breadcrumbs">
                            @include('seeker.common.breadcrumbs')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
