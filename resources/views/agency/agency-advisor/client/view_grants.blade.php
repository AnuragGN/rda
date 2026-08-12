<div class="row profile-view">
    <div class="col-md-9">
        <div class="card gn-shadow">
            <div class="header">
                <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_grant_view">
                    <span class="open"><i class="fas fa-caret-down"></i></span>
                    <span class="closed"><i class="fas fa-caret-right"></i></span>
                    Grant History
                </div>
                <div><a> {{ \App\Helpers\GnUtils::money($total_grant_amount) }}</a></div>
            </div>
            <div class="body address" id="id_grant_view">
                <table class="table-pending-grants">
                    <tbody>
                        <tr>
                            <th>Fund</th>
                            <th style="text-align: left;">Organization</th>
                            <th style="text-align: left;">Amount</th>
                            <th style="text-align: left;">Grant date</th>
                        </tr>
                        @foreach($grantData as $grant)
                        <tr>
                            <td>{{ $grant['fund_name'] }}</td>
                            <td style="text-align: left;">{{ $grant['grantee'] }}</td>
                            <td style="text-align: left;">{{ \App\Helpers\GnUtils::money($grant['amount']) }}</td>
                            <td style="text-align: left;">{{ \App\Helpers\GnUtils::customDate($grant['grant_date']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">

    </div>
</div>