<?php
/** @var \App\Models\OrganizationInfo $org */
$info = $org->getOrgInfo();
$story = $org->getStory();
?>

<html>
<head>
    <link href="./ma/css/print-pdf.css" rel="stylesheet">
    <link href={{  './' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">
</head>

<body class="gn-print org-print">

<!-- Define header and footer blocks before your content -->
@include(\App\Models\ClientInfo::clientViewFor('pdf.header'))

@include(\App\Models\ClientInfo::clientViewFor('pdf.footer'))

{{-- Wrap the content of your PDF inside a main tag --}}
<main>

    @if($org->image)
        <div class="logo">
            <img class="gn-shadow" src="{{url($org->image)}}" />
        </div>
    @endif

    <div class="title">{{$info->name}}</div>

    <div class="address">{!! $org->getInfoAddressTwoLine() !!}</div>

    @if($story->mission && strlen($story->mission))
        <div class="subtitle">Mission</div>
        <div>{!! $story->mission !!}</div>
    @endif

    @if($story->history && strlen($story->history))
        <div class="subtitle">History</div>
        <div>{!! $story->history !!}</div>
    @endif

    @if($story->programs && strlen($story->programs))
        <div class="subtitle">Programs</div>
        <div>{!! $story->programs !!}</div>
    @endif

    @if($story->volunteerism && strlen($story->volunteerism))
        <div class="subtitle">Volunteerism</div>
        <div>{!! $story->volunteerism !!}</div>
    @endif

</main>

</body>
</html>
