<?php
if (!isset($itemIndex) || $itemIndex < 0 || $itemIndex > 2) $itemIndex = 0;
if ($itemIndex == 0) {
    $image = "/ma/images/donate-01.jpg";
    $title = "Doctors without borders - United States of America";
} else if ($itemIndex == 1) {
    $image = "/ma/images/donate-02.jpg";
    $title = "Inspired by the past, engaged in the present, building for the future";
} else if ($itemIndex == 2) {
    $image = "/ma/images/donate-03.jpg";
    $title = "Scientific, psychological, spiritual advice during pandemic";
}
$cls = isset($class)? $class : 'promo-box';
?>

<div class="placeholder-box {{$cls}}">
    <img src={{$image}} />
    <p class="title">{{$title}}</p>
</div>
