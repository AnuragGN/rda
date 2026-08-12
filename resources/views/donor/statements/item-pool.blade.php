<?php
$collapsed = \App\Models\ClientConfig::value('FS_POOL_COLLAPSED');
if ($collapsed) {
    $poolOpen = 'none';
    $poolIconOpen = 'none';
    $poolIconClosed = 'inline';
} else {
    $poolOpen = 'block';
    $poolIconOpen = 'inline';
    $poolIconClosed = 'none';
}
$pvId = 'id-pool-' . $groupIndex . '-' . $poolIndex;

if (!isset($pool['subtype'])) {
    $pool['subtype'] = 'pool-default';
}
?>

<div class="row">
    <div class="col-12">
        <div class="fund-pool {{ $pool['subtype'] }}">

            <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values"
               title="Click to Expand / Collapse" data-target-id="{{$pvId}}">
                <span class="name">
                    <small id="id_pool_open" style="display: {{ $poolIconOpen }}"><i class="fas fa-minus-circle toggle-icon"></i></small>
                    <small id="id_pool_closed" style="display: {{ $poolIconClosed }}"><i class="fas fa-plus-circle toggle-icon"></i></small>
                    {{ $pool['name'] }}</span>
                <span class="amount"> {{ $pool['amount'] }}</span>
            </a>

            <div class="pool-values" id="{{$pvId}}" style="display: {{ $poolOpen }}">
                @foreach($pool['children'] as $i => $child)
                    @include("donor.statements.item", ['item' => $child, 'index' => $i])
                @endforeach
            </div>

        </div>
    </div>
</div>
