@if ($group['type'] == 'info')
    <div class="fund-st-info">{{$group['title']}}</div>
@elseif ($group['type'] == 'group-empty')
     <div></div>
@else

    @if(isset($group['title']))

        <div class="row">
            <div class="col-12">
                <h4 class="page-subtitle">{{$group['title']}}
                @if(isset($group['title-sm-right']))
                    <small style="float: right;">{{$group['title-sm-right']}}</small>
                    @endif
                </h4>
            </div>
        </div>

    @endif

    @if ($group['type'] == 'group')

        @forelse($group['items'] as $i => $item)
            @include(\App\Models\ClientInfo::clientViewFor("statements.item", "donor."), ['item' => $item, 'index' => $i])
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
@endif
