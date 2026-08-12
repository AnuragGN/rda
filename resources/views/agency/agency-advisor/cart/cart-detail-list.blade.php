
@forelse($cart as $i => $model)
    @include(\App\Models\ClientInfo::clientViewFor("agency.agency-advisor.cart.cart-detail-item"), ['model' => $model])
@empty
    @include("utils.data-not-found", [])
@endforelse
