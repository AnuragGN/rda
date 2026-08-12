@if ($item['type'] == 'single')
    @include("donor.statements.item-single", ['item' => $item, 'index' => $i])
@elseif ($item['type'] == 'pool')
    @include("donor.statements.item-pool", ['pool' => $item, 'poolIndex' => $i])
@else
    <p>Unknown item-type: {{$item['type']}}?</p>
@endif
