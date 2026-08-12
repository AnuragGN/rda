<table align="center" border="0" cellpadding="0" cellspacing="0" style="-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; background-color:#d7d2cb; border-collapse:collapse !important; height:100% !important; margin:0; mso-table-lspace:0pt; mso-table-rspace:0pt; padding:0; width:600px !important">
    <tbody>
    <tr>
        <td style="height:100% !important; width:100% !important">
            @include('hga.emails.header')
        </td>
    </tr>

    <tr>
        <td>
            <!-- BEGIN TEMPLATE // -->
            <table border="0" cellpadding="0" cellspacing="0" style="-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; border-collapse:collapse !important; border:0 none #dddddd; mso-table-lspace:0pt; mso-table-rspace:0pt; width:600px">

                <tbody>
                <tr>
                    <td>
                        <!-- BEGIN BODY // -->
                        <table style="background-color:#FFFFFF; border-bottom-color:#CCCCCC; border-bottom-style:solid; border-bottom-width:1px; border-top-color:#FFFFFF; border-top-style:solid; border-top-width:0;">
                            <tbody>

                            @yield('content')

                            </tbody>
                        </table>
                        <!-- // END BODY -->
                    </td>
                </tr>

                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            @include('hga.emails.footer')
        </td>
    </tr>
    </tbody>
</table>

{{--<p>&nbsp;</p>--}}
