<html>
<head>
    <link href="/ma/css/print-pdf.css" rel="stylesheet">
    {{--<link href={{ '/' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">--}}

    <link href="./ma/css/print-pdf.css" rel="stylesheet">
{{--    <link href={{ './' . \App\Models\ClientInfo::clientCss("print-pdf.css") }} rel="stylesheet">--}}
</head>

<style>

</style>

<body class="gn-print fs-print">

<!-- define header and footer blocks before your content -->
@include(\App\Models\ClientInfo::clientViewFor('pdf.header'))

@include(\App\Models\ClientInfo::clientViewFor('pdf.footer'))
<!-- Wrap the content of your PDF inside a main tag -->
<main>

    <div style="width: 100%; margin-top: -40px;">
        @include(\App\Models\ClientInfo::clientViewFor('daf-registration.form-review-new', 'agency.agency-advisor.'))
        <div style="height: 2rem;"></div>
    </div>

</main>
</body>
</html>
