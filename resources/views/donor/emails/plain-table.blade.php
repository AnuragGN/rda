<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Responsive Email</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <style>
        /* CLIENT-SPECIFIC STYLES */

        /* Prevent WebKit and Windows mobile changing default text sizes */
        body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}

        /* Remove spacing between tables in Outlook 2007 and up */
        /* table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} */

        /* Allow smoother rendering of resized image in Internet Explorer */
        img{-ms-interpolation-mode: bicubic;}

        /* RESET STYLES */
        img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
        table{border-collapse: collapse !important;}
        table tr td {padding-top:4px; padding-bottom:4px;}
        body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width: 525px) {

            /* ALLOWS FOR FLUID TABLES */
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img {
                margin: 0 auto !important;
            }

            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .m-hide {
                display: none !important;
            }

            .img-max {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
            }

            /* FULL-WIDTH TABLES */
            .responsive-table {
                width: 100% !important;
            }

            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding {
                padding: 10px 5% 15px 5% !important;
            }

            .padding-meta {
                padding: 30px 5% 0px 5% !important;
                text-align: center;
            }

            .no-padding {
                padding: 0 !important;
            }

            .section-padding {
                padding: 50px 15px 50px 15px !important;
            }

            /* ADJUST BUTTONS ON MOBILE */
            .m-button-container {
                margin: 0 auto;
                width: 100% !important;
            }

            .m-button {
                padding: 15px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
            }

        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] { margin: 0 !important; }

    </style>

</head>

<body style="margin: 0!important; padding: 0.5rem!important;">

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100% !important; font-family: Arial, sans-serif; color: #424242; font-size: 16px; line-height: 1.5;">
    <tbody>
    {{-- header --}}

    {{-- body --}}
    @yield('content')

    {{-- footer --}}
    </tbody>

</table>

</body>
</html>
