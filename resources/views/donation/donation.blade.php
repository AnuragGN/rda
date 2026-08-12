<?php
/** @var \App\Models\Donation $model */
// $model = \App\Models\Donation::getSampleInstance();
?>


<div class="donation-view">

    <h2 class="page-subtitle">
        Thank you!
    </h2>
    <p class="fw600">Your donation to <span class="fw700">{{ $model->getTargetName() }}</span> has been submitted successfully.</p>

    {{-- amount --}}
    <div class="row">
        {!! Form::label('amount', 'Amount ($)', ['class' => 'col-sm-3 col-form-label text-right']) !!}
        <div class="col-sm-8 value">{{$model->amount}}</div>
    </div>

    <div class="row">
        {!! Form::label('interval', 'Interval', ['class' => 'col-sm-3 col-form-label text-right']) !!}
        <div class="col-sm-8 value">
            {{ $model->getIntervalText() }}
        </div>
    </div>

    @if(!$model->isOneTime())
        <div class="row">
            <label class="col-sm-3 col-form-label text-right">Start Date</label>
            <div class="col-sm-8 value">
                {{ $model->start_date }}
            </div>
        </div>

        <div class="row">
            <label for="booking_date" class="col-sm-3 col-form-label text-right">Ends after</label>
            <div class="col-sm-8 value">
                @if($model->no_end)
                    Ongoing
                @else
                    {{ $model->occurrences }} occurrences
                @endif
            </div>
        </div>

    @endif

    {{-- dedicate to --}}
    @if($model->dedicated_to_name)
        <div class="row">
            <label for="dedicated_to" class="col-sm-3 col-form-label text-right">Dedicated to</label>
            <div class="col-sm-8 value">
                {{ $model->dedicated_to_name }}
            </div>
        </div>
    @endif

    @if($model->notify_to)
        <div class="row mt-3">
            <div class="col-md-12">
                <h5 class="form-group-title">Notify to</h5>
            </div>
        </div>

        <div class="row account-name">
            <label for="id_notify_fname" class="col-md-3 col-form-label text-right">Name</label>
            <div class="col-sm-8 value">
                {{ $model->notify_fname }} {{ $model->notify_lname }}
            </div>
        </div>

        <div class="row">
            <label for="id_notify_address_one" class="col-md-3 col-form-label text-right">Address Line 1</label>
            <div class="col-md-8 value">
                {{ $model->notify_address_one }}
            </div>
        </div>

        <div class="row">
            <label for="id_notify_address_two" class="col-md-3 col-form-label text-right">Address Line 2</label>
            <div class="col-md-8 value">
                {{ $model->notify_address_two }}
            </div>
        </div>

        <div class="row">
            <label for="id_notify_city" class="col-md-3 col-form-label text-right">City</label>
            <div class="col-md-4 value">
                {{ $model->notify_city }}
            </div>

            <label for="id_notify_state" class="col-md-1 col-form-label text-right">State</label>
            <div class="col-md-4 value">
                {{ $model->notify_state }}
            </div>
        </div>

        <div class="row">
            <label for="id_notify_country" class="col-md-3 col-form-label text-right">Country</label>
            <div class="col-md-4 value">
                {{ $model->notify_country }}
            </div>

            <label for="id_notify_zip" class="col-md-1 col-form-label text-right">ZIP</label>
            <div class="col-md-4 value">
                {{ $model->notify_zip }}
            </div>
        </div>
        {{-- end notify to --}}
    @endif

    <div class="row mt-4">
        <div class="col-md-12">
            <h5 class="form-group-title">Personal Information</h5>
        </div>
    </div>

    <div class="row account-name">
        <label for="id_fname" class="col-md-3 col-form-label text-right">Name</label>
        <div class="col-md-8 value">
            {{ $model->guest_fname }} {{ $model->guest_lname }}
        </div>
    </div>

    <div class="row">
        <label for="id_email" class="col-md-3 col-form-label text-right">Email</label>
        <div class="col-md-8 value">
            {{ $model->guest_email }}
        </div>
    </div>

    <div class="row">
        <label for="id_phone" class="col-md-3 col-form-label text-right">Phone #</label>
        <div class="col-sm-8 value">
            {{ $model->guest_phone }}
        </div>
    </div>

    @if($model->donor_org_name and strlen($model->donor_org_name) > 0)
        <div class="row">
            <label for="id_phone" class="col-md-3 col-form-label text-right">Organization Name</label>
            <div class="col-sm-8 value">
                {{ $model->donor_org_name }}
            </div>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-12">
            <h5 class="form-group-title">Address</h5>
        </div>
    </div>

    <div class="row">
        <label for="id_address_one" class="col-md-3 col-form-label text-right">Address Line 1</label>
        <div class="col-sm-8 value">
            {{ $model->guest_address_one }}
        </div>
    </div>

    @if($model->guest_address_two and strlen($model->guest_address_two) > 0)
        <div class="row">
            <label for="id_address_two" class="col-md-3 col-form-label text-right">Address Line 2</label>
            <div class="col-sm-8 value">
                {{ $model->guest_address_two }}
            </div>
        </div>
    @endif

    <div class="row">
        <label for="id_city" class="col-md-3 col-form-label text-right">City</label>
        <div class="col-md-4 value">
            {{ $model->guest_city }}
        </div>

        <label for="id_state" class="col-md-1 col-form-label text-right">State</label>
        <div class="col-md-4 value">
            {{ $model->guest_state }}
        </div>
    </div>

    <div class="row">
        <label for="id_country" class="col-md-3 col-form-label text-right">Country</label>
        <div class="col-md-4 value">
            {{ $model->guest_country }}
        </div>

        <label for="id_zip" class="col-md-1 col-form-label text-right">ZIP</label>
        <div class="col-md-4 value">
            {{ $model->guest_zip }}
        </div>
    </div>

    <br>
    <br>
</div>

