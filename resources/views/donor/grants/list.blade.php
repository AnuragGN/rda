
@forelse($models as $i => $model)
    @include("donor.grants.list-item", ['model' => $model])
@empty
    @include("utils.data-not-found", [])
@endforelse
