<div class="row profile-view">
    <div class="col-md-9">
        <div class="card gn-shadow">
            <div class="header">
                <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_gift_view">
                    <span class="open"><i class="fas fa-caret-down"></i></span>
                    <span class="closed"><i class="fas fa-caret-right"></i></span>
                    Gift History
                </div>
                <div><a> {{ \App\Helpers\GnUtils::money($total_gift_amount) }}</a></div>
            </div>
            <div class="body address" id="id_gift_view">
                <table class="table-pending-grants">
                    <tbody>
                        <tr>
                            <th>Fund</th>
                            <th style="text-align: left;">Donor</th>
                            <th style="text-align: left;">Amount</th>
                            <th style="text-align: left;">Gift date</th>
                        </tr>
                        @foreach($giftData as $gift)
                        <tr>
                            <td>{{ $gift['fund_name'] }}</td>
                            <td style="text-align: left;">{{ $gift['donor'] }}</td>
                            <td style="text-align: left;">{{ \App\Helpers\GnUtils::money($gift['amount']) }}</td>
                            <td style="text-align: left;">{{ \App\Helpers\GnUtils::customDate($gift['gift_date']) }}</td>
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