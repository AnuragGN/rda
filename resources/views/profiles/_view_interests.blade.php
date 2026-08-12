<?php
$interestAreas = \App\Models\ContactInterestArea::getInterestAreas();
$geographicAreas = \App\Models\ContactGeographicArea::getGeographicAreas();
$populations = \App\Models\ContactPopulationServed::getPopulationServed();
?>

<style>
    .area-title {
        border-bottom: 1px solid #eee;
        padding-bottom: 7px;
        margin-bottom: 0.5rem;
        padding-top: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>

<div class="area-title">Interest Areas</div>
@foreach($interestAreas as $interestArea)
    <div class="mb-3">
        <div class="fw600"><input class="mr-2" {{$interestArea->selected ? 'checked' : ''}} disabled="disabled" type="checkbox">{{$interestArea->interest_area}}</div>
        @foreach($interestArea->children as $child)
            <div class="mt-1 ml-4">
                <input class="mr-2" {{$child->selected ? 'checked' : ''}} disabled="disabled" type="checkbox">{{$child->interest_area}}</div>
        @endforeach
    </div>
@endforeach


<div class="area-title">Geographic Areas</div>
@foreach($geographicAreas as $geographicArea)
    <div class="mb-1">
        <input class="mr-2" {{$geographicArea->selected ? 'checked' : ''}} disabled="disabled" type="checkbox">{{$geographicArea->geographic_area_id}}
    </div>
@endforeach

<div class="area-title">Population Served</div>
@foreach($populations as $population)
    <div class="mb-1">
        <input class="mr-2" {{$population->selected ? 'checked' : ''}} disabled="disabled" type="checkbox">{{$population->population_served_id}}
    </div>
@endforeach
