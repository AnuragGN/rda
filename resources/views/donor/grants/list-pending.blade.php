
@forelse($models as $i => $model)
    <div class="row">
        <div class="col-12">
            <div class="row-history">
                <div class="fund-info">
                    <p class="fund-date">{{$model->org_name}}</p>
                    <p class="fund-to">{{ \App\Helpers\GnUtils::customDate($model->date_submitted) }}</p>
                    <p class="fund-to hide">{{$model->grant_description}}</p>
                </div>
                <div class="fund-granted">
                    <span class="fund-amount">{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
                </div>
            </div>
        </div>
    </div>
@empty
    @include("utils.data-not-found", [])
@endforelse
