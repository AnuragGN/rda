@extends ('agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Pending Recommendation'])

<section class="content">
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-9">
                    <h1 class="page-title two-column w100 mt-2">
                        <span></span>
                    </h1>
                    @include('agency.agency-advisor.cart.cart-detail-loader')
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const currentUrl = window.location.href;
    const urlParts = currentUrl.split('/');
    const id = urlParts[urlParts.indexOf('cart-detail') + 1];

    $(function() {
        jsAdvisorCartDetailLoader.init('/m/agency/cart-detail-ajax');
        jsAdvisorCartDetailLoader.getCartId(id);
        jsAdvisorCartDetailLoader.runLoadData();
        
    });
    </script>
@endsection
