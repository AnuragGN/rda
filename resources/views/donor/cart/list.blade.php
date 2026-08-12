
@forelse($models as $i => $model)
    @include(\App\Models\ClientInfo::clientViewFor("cart.list-item", "donor."), ['model' => $model])
@empty
    @include("utils.data-not-found", [])
@endforelse
