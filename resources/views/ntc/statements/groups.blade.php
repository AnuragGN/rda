
<style>
    table tr th, table tr td {
        text-align: right;
    }
    table tr th:first-child, table tr td:first-child {
        text-align: left;
    }
</style>

<table style="width: 100%">
    <tr>
        <th>Asset Name</th>
        <th>Value</th>
        <th>Quantity</th>
        <th>Last Price</th>
    </tr>
    @foreach($groups as $i => $group)
        <tr>
            <td>{{ $group['ticker'] . ":" . $group['product'] }}</td>
            <td>{{ $group['total'] }}</td>
            <td>{{ $group['units'] }}</td>
            <td>{{ $group['price'] }}</td>
        </tr>
    @endforeach
</table>
