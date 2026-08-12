<?php
$extraCls = 'indent-' . $level;

if (!isset($item['subtype'])) $item['subtype'] = 'default';
$cls = (strpos($item['subtype'], 'cls-') === 0) ? $item['subtype'] : '';
?>

<tr class="fund-kv {{$cls}} {{$extraCls}} ">
    <td>
        @if( $item['subtype'] == 'fund-linked')
            {{ $item['link'] ? $item['link'] . ':' : ''}} {{ $item['name'] }}
        @else
            {{ $item['name'] }}
        @endif
    </td>
    <td>{{ $item['amount'] }}</td>
</tr>

