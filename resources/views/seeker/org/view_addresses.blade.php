
@foreach($addresses as $address)
    @if($address['is_primary'] == 'Y')

        <div class="card card-info org-profile-view">
            <div class="card-header text-uppercase">
                <span>{{$address['label']}} Address</span>
                <div class="card-tools">
                    <div><a href="{{route('gs-org-address-edit', ['organization_id' => $org->organization_id, 'address_type' => $address['type']])}}">Edit</a></div>
                </div>
            </div>
            <div class="card-body address" id="id_address_view_{{$address['type']}}">
                <span>{!! $address['address'] !!}</span>
                <span>
                    <button type="button" class="icon-primary" data-toggle="tooltip" data-placement="top" title="Primary address">
                        <i class="fas fa-star"></i>
                    </button>
                </span>
            </div>
        </div>

    @endif
@endforeach


@foreach($addresses as $address)
    @if($address['is_primary'] != 'Y' && $address['address'])

        <div class="card card-info org-profile-view">
            <div class="card-header text-uppercase">
                <span>{{$address['label']}} Address</span>
                <div class="card-tools">
                    <div><a href="{{route('gs-org-address-edit', ['organization_id' => $org->organization_id, 'address_type' => $address['type']])}}">Edit</a></div>
                </div>
            </div>
            <div class="card-body address" id="id_address_view_{{$address['type']}}">
                <span>{!! $address['address'] !!}</span>
            </div>
        </div>

    @endif
@endforeach


<div class="row profile-view">

    <div class="col-md-12">
        @foreach($addresses as $address)
            @if($address['is_primary'] != 'Y' && $address['address'] == null)
                <div style="float: right">
                    <a href="{{route('gs-org-address-edit', ['organization_id' => $org->organization_id, 'address_type' => $address['type']])}}"
                       class="txt-btn-sm mb-4 ml-2" style="float: right">
                        <i class="fas fa-plus-circle toggle-icon"></i>
                        Add {{$address['label']}} Address
                    </a>
                </div>
            @endif
        @endforeach
    </div>

</div>
