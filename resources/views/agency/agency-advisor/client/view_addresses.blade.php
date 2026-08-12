<div class="row profile-view">

    @foreach($addresses as $address)
        @if($address['is_primary'] == 'Y')
            <div class="col-md-9">
                <div class="card gn-shadow">
                    <div class="header">
                        <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_address_view_{{$address['type']}}">
                            <span class="open"><i class="fas fa-caret-down"></i></span>
                            <span class="closed"><i class="fas fa-caret-right"></i></span>
                            {{$address['label']}} Address
                        </div>
                    </div>
                    <div class="body address" id="id_address_view_{{$address['type']}}">
                        <span>{!! $address['address'] !!}</span>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @foreach($addresses as $address)
        @if($address['is_primary'] != 'Y' && $address['address'])
            <div class="col-md-9">
                <div class="card gn-shadow">
                    <div class="header">
                        <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer" data-child-id="id_address_view_{{$address['type']}}">
                            <span class="open"><i class="fas fa-caret-down"></i></span>
                            <span class="closed"><i class="fas fa-caret-right"></i></span>
                            {{$address['label']}} Address</div>
                        
                    </div>
                    <div class="body" id="id_address_view_{{$address['type']}}">{!! $address['address'] !!}</div>
                </div>
            </div>
        @endif
    @endforeach
</div>
