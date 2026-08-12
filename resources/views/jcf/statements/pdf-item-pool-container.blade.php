<?php
$extraCls = 'indent-' . $level;

if (!isset($pool['subtype'])) $pool['subtype'] = 'default';
$cls = (strpos($pool['subtype'], 'cls-') === 0) ? $pool['subtype'] : '';
?>

<tr class="fund-pool {{ $pool['subtype'] }} {{$extraCls}}">
    <td class="{{$cls}}">{{ $pool['name'] }}</td>
    <td class="{{$cls}}">{{ $pool['amount'] }}</td>
</tr>

@foreach($pool['children'] as $i => $child)
    @include("jcf.statements.pdf-item", ['item' => $child, 'index' => $i, 'level' => $level + 1])
@endforeach
