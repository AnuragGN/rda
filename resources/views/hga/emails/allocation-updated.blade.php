
@extends('hga.emails.layout')

@section('content')
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-12">
                    <p>Dear {{ $name }}, </p>
                    <p>The following investment change request has been approved and processed.</p>
                    <div class="row">

                        @foreach($updates as $key => $val)
                            <div class="row form-group ">
                                {{ $val->pool_name }} : {{$val->requested_allocation}} %
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


