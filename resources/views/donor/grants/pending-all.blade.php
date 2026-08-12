
@if(count($grants))
    @if ($page == 1)
        <h3 class="page-subtitle uppercase mt-2">
            <span>Pending Grants</span>
        </h3>
    @endif

    @foreach($grants as $fund => $items)
        <h4 class="page-subtitle mt-4 mb-2" style="border-bottom: 1px solid #a4a4a4;">From {{$fund}}</h4>

        <table class="table-pending-grants">
            <tr>
                <th>Organization</th>
                <th>Amount</th>
                {{--<th>Submitted On</th>--}}
                <th>Status</th>
            </tr>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['org'] }}</td>
                    <td>{{ $item['amount'] }}</td>
                    {{--<td>{{ $item['date_submitted'] }}</td>--}}
                    <td>
                        @if(\App\Models\ClientInfo::isCCT())
                            {{ $item['grant_status'] }}
                        @else
                            @if($item['status'] == 'N')
                                Approval Pending
                            @else
                                @if(\App\Models\ClientInfo::isJCF())
                                    Expected payment date {{ $item['default_payment_date'] }}
                                @else
                                    Approved on {{$item['approved_date']}}
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endforeach
@endif
