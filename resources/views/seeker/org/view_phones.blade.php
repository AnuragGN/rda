
@foreach($phones as $phone)
    @if($phone['is_primary'])
        <div class="row-phone">
        <span>
            <small class="fw600 phone-label">
                {{$phone['phone_type']}} Phone
                <button type="button" class="icon-primary" data-toggle="tooltip" data-placement="top" title="Primary phone">
                    <i class="fas fa-star"></i>
                </button>
            </small>
            {{$phone['phone_number']}}
        </span>
            <a class="txt-btn-sm" href="{{route('gs-org-phone-edit', [$org->organization_id, $phone->phone_type])}}">EDIT</a>
        </div>
    @endif
@endforeach

@foreach($phones as $phone)
    @if(!$phone['is_primary'])
        <div class="row-phone">
        <span>
            <small class="fw600 phone-label">{{$phone['phone_type']}} Phone</small>
            {{$phone['phone_number']}}
        </span>
            <a class="txt-btn-sm" href="{{route('gs-org-phone-edit', [$org->organization_id, $phone->phone_type])}}">EDIT</a>
        </div>
    @endif
@endforeach

@foreach($org->canAddPhoneTypes() as $type)
    <div class="row-phone">
        <span>
            <a href="{{route('gs-org-phone-edit', [$org->organization_id, $type['phone_type']])}}" class="txt-btn-sm">
                <i class="fas fa-plus-circle toggle-icon"></i> Add {{$type['label']}} Phone
            </a>
        </span>
    </div>
@endforeach
