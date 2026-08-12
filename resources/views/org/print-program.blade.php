<html>
<head>
    <link href="./ma/css/print-pdf.css" rel="stylesheet">
    <link href={{ './' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">
</head>

<body class="gn-print org-print">

<!-- Define header and footer blocks before your content -->
@include(\App\Models\ClientInfo::clientViewFor('pdf.header'))

@include(\App\Models\ClientInfo::clientViewFor('pdf.footer'))

{{-- Wrap the content of your PDF inside a main tag --}}
<main>

    @if($program->image)
        <div class="logo">
            <img class="gn-shadow" src="{{url($program->image)}}" />
        </div>
    @endif

    <div class="title">{{$program->title}}</div>

    <div>
        A Project of {!! $program->organization->name !!}
        <br><span class="font-small grey600 fw400">Amount needed:</span>
        <span class="fw600 text-accent">{{ \App\Helpers\GnUtils::money($program->total_requested) }}</span>
    </div>


    <div class="subtitle">Summary</div>
    <div>{!! $program->summary !!}</div>


</main>

</body>
</html>