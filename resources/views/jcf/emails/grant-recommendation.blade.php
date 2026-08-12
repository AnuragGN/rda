@extends('donor.emails.plain-table')
@section('content')
    @foreach($grants as $index => $data)
        <tr>
            <td align="left">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100% !important; max-width: 600px;" class="wrapper">
                    <tbody>
                    <tr>
                        <td colspan="2" style="font-weight: 600; padding-bottom: 0.5rem; border-bottom: 1px solid #c4c4c4; margin-bottom: 9px;">Recommendation from {{ $data['fund'] }} ({{$data['fund_id']}})</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 0.6rem; padding-right: 0.5rem; font-size: 0.875rem; color: #616161; font-weight: 600;">Organization</td>
                        <td style="padding-top: 0.6rem; color: #000080;">{{ $data['organization'] }} </td>
                    </tr>
                    <tr>
                        <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Address</td>
                        {{--<td><a disabled href="" style="pointer-events: none; cursor:text; color: #424242; text-decoration: none;">{!! $data['address'] !!}</a> </td>--}}
                        <td>{!! $data['address'] !!}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Amount</td>
                        <td>{{ $data['amount'] }}</td>
                    </tr>
                    @if (\App\Models\ClientConfig::feature('GRANTING_FREQUENCY') && isset($data['frequency']))
                        <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">{{\App\Models\GrantForm::frequencyLabel()}}</td>
                        <td>{{ $data['frequency'] }}</td>
                    @endif
                    @if(strlen($data['grant_purpose']) > 0)
                        <tr>
                            <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Grant Purpose</td>
                            <td>{{ $data['grant_purpose'] }}</td>
                        </tr>
                    @endif
                    @if(strlen($data['note']) > 0)
                        <tr>
                            <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Notes</td>
                            <td>{{ $data['note'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Donor</td>
                        <td>{{ $name }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 0.875rem; color: #616161; font-weight: 600;">Anonymous</td>
                        <td> {{ $data['anonymous'] }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        @if (count($grants) > (1+$index))
            <tr><td> &nbsp; </td></tr>
        @endif
    @endforeach
    <tr>
        <td align="left" style="font-size: 0.875rem!important;">
            <div>
                <p>Thank you for submitting your recommendation to the Jewish Community Foundation via JCFConnect!  All grants submitted by midnight on Sunday will be mailed out by the end of that week.</p>
                <p>You will be able to view your new grant recommendations right away under the pending section of your fund on the JCFConnect Dashboard. Simply log into JCFConnect and click the MORE button next to your fund on the dashboard. Once the funds are posted, they will appear in the Fund Balance page.</p>
                <p>If you have any questions, please email grants@jcfsandiego.org.</p>
            </div>
        </td>
    </tr>
    <tr>
        <td align="left" style="font-size: 0.75rem!important; color: #757575;">
            <div>
                <p>In our continued effort to be environmentally responsible we will provide grant distribution summaries upon request only. This information is readily available on JCFConnect. To receive a grant distribution summary monthly, please email grants@jcfsandiego.org or call (858) 279-2740</p>
                <hr style="margin: 1rem 0; background-color: #c4c4c4; height: 0.5px; border: 0;" />
                <p>This is a system-generated e-mail.</p>
            </div>
        </td>
    </tr>
@endsection
