<?php
$pvId = 'id-pool-container-' . $groupIndex . '-' . $poolIndex;
?>

<div class="row">
    <div class="col-12">
        <div class="fund-pool pool-container">

            <a href="javascript:void(0);" class="pool-kv js_toggle_pool_values"
               title="Click to Expand / Collapse" data-target-id="{{$pvId}}">
                <span class="name">
                    <small id="id_pool_open" style="display: none"><i class="fas fa-minus-circle toggle-icon"></i></small>
                    <small id="id_pool_closed" style="display: inline"><i class="fas fa-plus-circle toggle-icon"></i></small>
                    {{ $pool['name'] }}</span>
                <span class="amount"> {{ $pool['amount'] }}</span>
            </a>

            <div class="pool-values" id="{{$pvId}}" style="display: none">
                @foreach($pool['children'] as $i => $child)
                    @include("donor.statements.item", ['item' => $child, 'index' => $i])
                @endforeach
            </div>

        </div>
    </div>
</div>
