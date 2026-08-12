@if ($item['type'] == 'single')
    @include("jcf.statements.pdf-item-single", ['item' => $item, 'index' => $i, 'level' => $level])
@elseif ($item['type'] == 'pool')
    @include("jcf.statements.pdf-item-pool", ['pool' => $item, 'poolIndex' => $i, 'level' => $level])
@elseif ($item['type'] == 'pool-container')
    @include("jcf.statements.pdf-item-pool-container", ['pool' => $item, 'poolIndex' => $i, 'level' => $level])
@else
    <p>Unknown item-type: {{$item['type']}}?</p>
@endif
