@if(false)
    - subtypes for single item
    self-link -
    named-link -
    fund-linked -
    default -
@endif

<div class="fund-kv">
    @if( $item['subtype'] == 'self-link')
        <a href="{{ $item['link'] }}" class="rv-udl"> {{ $item['name'] }}</a>
    @elseif( $item['subtype'] == 'named-link')
        <span> {{ $item['name'] }}
            (<a href="{{ $item['link'] }}" class="rv-uidl">{{ $item['linkTitle'] }}</a>)
        </span>
    @elseif( $item['subtype'] == 'fund-linked')
        <span><a class="js_external_fund_url" href="javascript:void(0);" data-fund="{{ $item['link'] }}">{{ $item['link'] }}</a>: {{ $item['name'] }}</span>
    @else
        <span>{{ $item['name'] }}</span>
    @endif
    <span>{{ $item['amount'] }}</span>
</div>

