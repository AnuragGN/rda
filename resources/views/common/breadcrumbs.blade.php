<?php
$breadcrumbs = \App\Helpers\GnUtils::getBreadcrumbs();
$homeUrl = \App\Helpers\GnUtils::userHomeUrl();
?>

@if(count($breadcrumbs))
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{$homeUrl}}">Home</a></li>
        @foreach($breadcrumbs as $breadcrumb)
            @if(isset($breadcrumb['link']) and $breadcrumb['link'])
                <li class="breadcrumb-item"><a href="{{$breadcrumb['link']}}">{{$breadcrumb['title']}}</a></li>
            @else
                <li class="breadcrumb-item active" >{{$breadcrumb['title']}}</li>
            @endif
        @endforeach
    </ol>
@endif
