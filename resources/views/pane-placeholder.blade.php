<?php
$i = rand(1,99);
$index1 = $i % 3;
$index2 = ($index1+1) % 3;
$index3 = ($index2+1) % 3;
$cls = isset($class)? $class : 'promo-box';
$classTitle = isset($classTitle) ? $classTitle : '';
?>

@include('content.promo')

@if(false)
    @if(\App\Models\ClientInfo::isJSV() or \App\Models\ClientInfo::isHGA() or \App\Models\ClientInfo::isMercy() or \App\Models\ClientInfo::isNIF() or \App\Models\ClientInfo::isGMF() or \App\Models\ClientInfo::isGNA() or \App\Models\ClientInfo::isJCF())
        @include('content.promo')
    @else
        <h3 class="page-subtitle mt-2 {{$cls}} {{$classTitle}}">Articles</h3>
        @include('placeholder', ['itemIndex' => $index1])
        @include('placeholder', ['itemIndex' => $index2])
        @include('placeholder', ['itemIndex' => $index3])
    @endif
@endif
