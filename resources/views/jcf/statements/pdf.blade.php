<html>
<head>
    <link href="./ma/css/print-pdf.css" rel="stylesheet">
    <link href={{ './' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">
</head>

<body class="gn-print fs-print">

<!-- define header and footer blocks before your content -->
@include(\App\Models\ClientInfo::clientViewFor('pdf.header'))

@include(\App\Models\ClientInfo::clientViewFor('pdf.footer'))

<!-- Wrap the content of your PDF inside a main tag -->
<main>

    <div style="width: 100%; margin-top: -40px">
        <div class="title-box uppercase">
            <span class="title">{{ $fund['fund_name'] }}</span>
            <span class="date">Market Value as of {{\App\Helpers\GnUtils::customDate($fund['statement_date'])}}</span>
        </div>

        @foreach($groups as $groupIndex => $group)
            @if ($group['type'] == 'group-empty')
                <div class="fund-st-space">{{$group['title']}}</div>
            @endif

            @if(isset($group['title']))
                <div class="sub-title">{{$group['title']}}
                    @if(isset($group['title-sm-right']))
                        <small style="float: right;">{{$group['title-sm-right']}}</small>
                    @endif
                </div>
            @endif

            @foreach($group['items'] as $index => $item)
                @if ($item['type'] == 'single')
                    <table class="item">
                        @include("jcf.statements.pdf-item-single", ['item' => $item, 'level' => 1])
                    </table>
                @elseif ($item['type'] == 'pool')
                    <table class="pool">
                        @include("jcf.statements.pdf-item-pool", ['pool' => $item, 'poolIndex' => $index, 'level' => 1])
                    </table>
                @elseif ($item['type'] == 'pool-container')
                    <table class="pool-container">
                        @include("jcf.statements.pdf-item-pool-container", ['pool' => $item, 'poolIndex' => $index, 'level' => 1])
                    </table>
                @else
                    <p>Unknown item-type: {{$item['type']}}?</p>
                @endif
            @endforeach
        @endforeach
    </div>

</main>
</body>
</html>
