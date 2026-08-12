<div class="row profile-view">

    @foreach($addresses as $address)
        @if($address['is_primary'] == 'Y')
            <div class="col-md-12">
                <div class="card gn-shadow">
                    <div class="header">
                        <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_address_view_{{$address['type']}}">
                            <span class="open"><i class="fas fa-caret-down"></i></span>
                            <span class="closed"><i class="fas fa-caret-right"></i></span>
                            {{$address['label']}} Address
                        </div>
                        <div><a href="{{route('profile-address-edit', ['type' => $address['type']])}}">Edit</a></div>
                    </div>
                    <div class="body address" id="id_address_view_{{$address['type']}}">
                        <span>{!! $address['address'] !!}</span>
                        <span>
                            <button type="button" class="icon-primary" data-toggle="tooltip" data-placement="top" title="Primary address">
                                <i class="fas fa-star"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @foreach($addresses as $address)
        @if($address['is_primary'] != 'Y' && $address['address'])
            <div class="col-md-12">
                <div class="card gn-shadow">
                    <div class="header">
                        <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_address_view_{{$address['type']}}">
                            <span class="open"><i class="fas fa-caret-down"></i></span>
                            <span class="closed"><i class="fas fa-caret-right"></i></span>
                            {{$address['label']}} Address</div>
                        <div><a href="{{route('profile-address-edit', ['type' => $address['type']])}}">Edit</a></div>
                    </div>
                    <div class="body" id="id_address_view_{{$address['type']}}">{!! $address['address'] !!}</div>
                </div>
            </div>
        @endif
    @endforeach

    <div class="col-md-12">
        @foreach($addresses as $address)
            @if($address['is_primary'] != 'Y' && $address['address'] == null)
                <div style="float: right">
                    <a href="{{route('profile-address-edit', ['type' => $address['type']])}}"
                       class="txt-btn-sm mb-4 ml-2" style="float: right">
                        <i class="fas fa-plus-circle toggle-icon"></i>
                        Add {{$address['label']}} Address
                    </a>
                </div>
            @endif
        @endforeach
    </div>

</div>
