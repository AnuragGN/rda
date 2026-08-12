
@foreach($phones as $phone)
    @if($phone['is_primary'])
        <div class="row-phone">
        <span>
            <small class="fw600 phone-label">
                {{$phone['label']}} Phone
                <button type="button" class="icon-primary" data-toggle="tooltip" data-placement="top" title="Primary phone">
                    <i class="fas fa-star"></i>
                </button>
            </small>
            {{$phone['phone_number']}}
        </span>
        </div>
    @endif
@endforeach

@foreach($phones as $phone)
    @if(!$phone['is_primary'])
        <div class="row-phone">
        <span>
            <small class="fw600 phone-label">{{$phone['label']}} Phone</small>
            {{$phone['phone_number']}}
        </span>
        </div>
    @endif
@endforeach

