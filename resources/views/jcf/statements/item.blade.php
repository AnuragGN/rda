@if ($item['type'] == 'single')
    @include("donor.statements.item-single", ['item' => $item, 'index' => $i])
@elseif ($item['type'] == 'pool')
    @include(\App\Models\ClientInfo::clientViewFor("statements.item-pool", "donor."), ['pool' => $item, 'poolIndex' => $i])
@elseif ($item['type'] == 'pool-container')
    @include("jcf.statements.item-pool-container", ['pool' => $item, 'poolIndex' => $i])
@else
    <p>Unknown item-type: {{$item['type']}}?</p>
@endif
