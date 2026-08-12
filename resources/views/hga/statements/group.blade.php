
<div class="row">
    <div class="col-12">
        <h4 class="page-subtitle">{{$group['title']}}</h4>
    </div>
</div>

@if ($group['type'] == 'group')

    @forelse($group['items'] as $i => $item)
        @include("donor.statements.item", ['item' => $item, 'index' => $i])
    @empty
        @include("utils.data-not-found", [])
    @endforelse

@elseif ($group['type'] == 'balance')

    @forelse($group['items'] as $i => $item)
        @include("donor.statements.item", ['item' => $item, 'index' => $i])
    @empty
        @include("utils.data-not-found", [])
    @endforelse

@else

    <p>Unknown group-type: {{$group['type']}}?</p>

@endif
