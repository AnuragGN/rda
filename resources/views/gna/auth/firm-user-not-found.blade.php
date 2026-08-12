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
                    You do not have a DAF account at Charitable Gifting Fund. You can open a new DAF using the link provided below, or contact us for any assistance.
                </strong>
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
