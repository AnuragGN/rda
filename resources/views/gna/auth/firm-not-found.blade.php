@extends('layouts.main', ['container' => 'container login-container'])
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-12 client-info">
            <h1 class="page-title mb-4">
                Charitable Gifting Fund
            </h1>
            <p class="mb-4">Charitable Gifting Fund specializes in planned-giving administrative services tailored to your specific needs. As the go-to resource for financial services organizations, we offer comprehensive solutions for charitable gift instruments and provide expert planned giving advice to affluent investors.</p>
            <div class="alert alert-warning mt-4 mb-4 text-center" style="background-color: #eef2f4;border-color: #eef2f4;">
                <strong>
                    {{--{!! $display_message ?? 'The specified firm does not exist in our records.' !!}--}}
                    We couldn’t validate your firm details. The information may be missing or incorrect.
                </strong>
            </div>
            <div class="row justify-content-center mb-1">
                <div class="col-12">
                    <span>If you believe this firm should be available, please reach out to <strong>Charitable Gifting Fund</strong> for assistance in adding your firm.</span>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('login') }}" class="btn btn-link">Home</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('daf-account') }}" class="btn btn-link">Open a Donor-Advised fund</a>
                </div>
            </div>
        </div>
    </div>
@endsection
