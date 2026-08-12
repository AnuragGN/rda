<?php
$extraCls = 'indent-' . $level;
?>

<tr class="fund-pool {{ $pool['subtype'] }} {{$extraCls}}">
    <td>{{ $pool['name'] }}</td>
    <td>{{ $pool['amount'] }}</td>
</tr>

@if($print == 'full')
    @foreach($pool['children'] as $i => $child)
        @include("jcf.statements.pdf-item", ['item' => $child, 'index' => $i, 'level' => $level + 1])
    @endforeach
@endif
