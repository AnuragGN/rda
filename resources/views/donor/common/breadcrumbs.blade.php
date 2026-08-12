<?php
$breadcrumbs = \App\Helpers\GnUtils::getBreadcrumbs();
?>

@if(count($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('donor-home')}}">Home</a></li>
            @foreach($breadcrumbs as $breadcrumb)
                @if(isset($breadcrumb['link']) and $breadcrumb['link'])
                    <li class="breadcrumb-item"><a href="{{$breadcrumb['link']}}">{{$breadcrumb['title']}}</a></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb['title']}}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
