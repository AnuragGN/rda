<?php
$loggedInRequired = true;
?>

@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body" style="min-height: 50vh">

            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <p class="form-title th-color">Thank You</p>
                    </div>
                    <p>
                        Thank you for choosing OAKWOOD Foundation to manage your Donor-Advised Fund. Upon review of your application,
                        OAKWOOD Foundation will send final documentation for signature.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
