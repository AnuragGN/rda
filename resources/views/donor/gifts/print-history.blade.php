<html>
<head>
    <link href="./ma/css/print-pdf.css" rel="stylesheet">
    <link href={{ './' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">
</head>

<body class="gn-print gg-print">

<!-- define header and footer blocks before your content -->
@include(\App\Models\ClientInfo::clientViewFor('pdf.header'))

@include(\App\Models\ClientInfo::clientViewFor('pdf.footer'))

<!-- Wrap the content of your PDF inside a main tag -->
<main>

    <div class="title">
        @if(\App\Helpers\GnUtils::isDonorSession())
            {{ $custom->text->GIFT_HISTORY }}
        @else
            Contribution History
        @endif
    </div>

    @if (isset($params['startDate']) && isset($params['endDate']))
        <div class="filter">
            From {{\App\Helpers\GnUtils::customDate(date($params['startDate']))}}
            to {{\App\Helpers\GnUtils::customDate(date($params['endDate']))}}
        </div>
    @endif

    <div style="clear: both;"></div>
    <table>
        <tr>
            <th>Date</th>
            <th>Donor</th>
            <th>Amount</th>
        </tr>
        @foreach ($gifts as $key => $gift)
            <tr>
                <td>{{ \App\Helpers\GnUtils::customDate($gift->gift_date) }}</td>
                <td>{{$gift->donor}}</td>
                <td>{{ \App\Helpers\GnUtils::money($gift->amount) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="total">Total {{ \App\Models\ClientInfo::isJCF() ? '' : '=' }} {{ \App\Helpers\GnUtils::money($total) }}</div>

    <!--
    <p style="page-break-after: always;">Content Page 1</p>
    <p style="page-break-after: never;">Content Page 2</p>
    -->

</main>
</body>
</html>
