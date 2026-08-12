
<?php
$pvId = 'id-pool-' . $groupIndex . '-' . $poolIndex;
?>

<div class="row">
    <div class="col-12">
        <div class="fund-pool">

            <div class="pool-kv">
                <span class="name">
                    <a href="javascript:void(0);" class="toggle-icon js_toggle_pool_values"
                       title="Expand / Minimize" data-target-id="{{$pvId}}">
                        <small><i class="fas fa-minus-circle"></i><i class="fas fa-plus-circle hide"></i></small>
                    </a> {{ $pool['name'] }}</span>
                <span class="amount"> {{ $pool['amount'] }}</span>
            </div>

            <div class="pool-values" id="{{$pvId}}">
                @foreach($pool['children'] as $i => $child)
                    @include("donor.statements.item", ['item' => $child, 'index' => $i])
                @endforeach
            </div>

        </div>
    </div>
</div>
