
@foreach($items as $item)
    <div class="catalog">

        @if($item['image'])
            <div class="image">
                <a href="{{$item['title-link']}}"><img src="{{$item['image']}}" alt=""></a>
            </div>
        @endif

        <div class="info">
            <div class="title">
                <a href="{{$item['title-link']}}">{{ $item['title'] }}</a>
            </div>
            <div class="sub-title text-muted"> {{ $item['sub-title'] }}</div>
            <div class="description">{!! $item['description'] !!}</div>

            @if($item['mag-link'])
                <div class="mag-link">
                    <a href="{{ $item['mag-link'] }}" class="btn btn-theme btn-sm">{{ $custom->text->MAKE_A_GRANT }}</a>
                </div>
            @endif
        </div>

    </div>

@endforeach
