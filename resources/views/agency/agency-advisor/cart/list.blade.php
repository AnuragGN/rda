
@forelse($cart as $i => $model)
    @include(\App\Models\ClientInfo::clientViewFor("agency.agency-advisor.cart.list-item"), ['model' => $model])
@empty
    @include("utils.data-not-found", [])
@endforelse
