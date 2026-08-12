<?php
$topCharities = [];
if (\App\Models\ClientInfo::isHGA()) {
    $contactId =  \App\Models\Contact::sessionContactId();
    $fundsIds = \App\Models\ContactFund::getViewableFundIdsByContactId($contactId);
    $topCharities = \App\Models\GrantHistory::whereIn('fund_id', $fundsIds)->OrderBy('amount', 'desc')->take(10)->get();
}
?>
@if(count($topCharities))
    <h3 class="page-subtitle uppercase mt-2">Top Charities</h3>
    <table class="table-pending-grants">
        <tbody>
        <tr>
            <th>Organization</th>
            <th>Amount</th>
        </tr>

        @foreach($topCharities as $charity)
            <tr>
                <td>{{$charity->grantee}}</td>
                <td>{{\App\Helpers\GnUtils::StrToMoney($charity->amount)}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endif
