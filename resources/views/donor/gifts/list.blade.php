
@forelse($models as $i => $model)
    @include("donor.gifts.list-item", ['model' => $model])
@empty
    @include("utils.data-not-found", [])
@endforelse
