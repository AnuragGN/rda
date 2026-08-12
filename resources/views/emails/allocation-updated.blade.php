
@extends('donor.emails.plain-text')

@section('content')
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-12">
                    <p>Dear {{ $name }}, </p>
                    <p>Fund allocation changed successfully.</p>
                    <div class="row">

                        @foreach($updates as $key => $val)
                            <div class="row form-group ">
                                {{ $val->pool_name }} : {{$val->requested_allocation}} %
                            </div>
                        @endforeach

                    </div>
                    <p>Regards, <br>Support Team</p>
                    <hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
                    <p><small>This is a system-generated e-mail.</small></p>
                </div>
            </div>
        </div>
    </div>
@endsection


