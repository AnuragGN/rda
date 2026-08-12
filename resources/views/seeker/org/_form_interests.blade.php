<?php
$interestAreas = \App\Models\OrgInterestArea::getInterestAreas($orgId);
$geographicAreas = \App\Models\OrgGeographicArea::getGeographicAreas($orgId);
$populations = \App\Models\OrgPopulationServed::getPopulationServed($orgId);
?>
<script>
    function onParentSelectionChanged(that, item) {
        var cls = 'child-' + item;
        $("." + cls).each(function() {
            this.checked = that.checked;
        });
    }
</script>

<div class="row row-page-title" id="id_title_interests">
    <div class="col-12">
        <h4 class="page-title uppercase mt-0">Areas of Interest</h4>
    </div>
</div>

@foreach($interestAreas as $interestArea)
    <div class="mb-3 ml-3">
        <div class="fw600">
            <input {{$interestArea->selected ? 'checked' : ''}}
                   class="mr-2"
                   name="interest_area_id[]"
                   value="{{$interestArea->interest_area_id}}"
                   onclick="onParentSelectionChanged(this, this.value)"
                   type="checkbox">{{$interestArea->interest_area}}
        </div>
        @foreach($interestArea->children as $child)
            <div class="mt-1 ml-4">
                <input {{$child->selected ? 'checked' : ''}}
                       class="mr-2 child-{{$interestArea->interest_area_id}}"
                       name="interest_area_id[]"
                       value="{{$child->interest_area_id}}"
                       type="checkbox">{{$child->interest_area}}</div>
        @endforeach
    </div>
@endforeach

<div class="row row-page-title">
    <div class="col-12">
        <h4 class="page-title uppercase">Geographic Areas</h4>
    </div>
</div>

<div class="row">
    @foreach($geographicAreas as $geographicArea)
        <div class="col-md-6 mb-1">
            <input {{$geographicArea->selected ? 'checked' : ''}}
                   class="ml-3 mr-2"
                   name="geographic_area_id[]"
                   value="{{$geographicArea->geographic_area_id}}"
                   type="checkbox">{{$geographicArea->geographic_area_id}}
        </div>
    @endforeach
</div>

<div class="row row-page-title">
    <div class="col-12">
        <h4 class="page-title uppercase">Population Served</h4>
    </div>
</div>

<div class="row">
@foreach($populations as $population)
    <div class="col-md-6 mb-1">
        <input {{$population->selected ? 'checked' : ''}}
               class="ml-3 mr-2"
               name="population_served_id[]"
               value="{{$population->population_served_id}}"
               type="checkbox">{{$population->population_served_id}}
    </div>
@endforeach
</div>

<hr>
<div class="form-group row">
    <div class="col-md-4">
        {!! Form::submit('Submit', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent w100']) !!}
    </div>
</div>

<div class="floating-btn text-center">
    {!! Form::submit('Submit', ['name' => 'save', 'id' =>'id_save_btn2', 'class' => 'btn btn-accent', 'style' => 'width:200px;']) !!}
</div>

<script>
    function isInterestsOnScreen() {
        var curPos = $('#id_title_interests').offset();
        var curTop = curPos.top + 50 - $(window).scrollTop();
        var screenHeight = $(window).height();

        console.log("curTop: " + curTop + ", screenHeight: " + screenHeight);
        return (curTop > 150);
    }
    function isSaveBtnOnScreen() {
        var curPos = $('#id_save_btn').offset();
        var curTop = curPos.top + 50 - $(window).scrollTop();
        var screenHeight = $(window).height();

        console.log("curTop: " + curTop + ", screenHeight: " + screenHeight);
        return (curTop <= screenHeight);
    }

    function manageBtnView() {
        var interestsOnScreen = isInterestsOnScreen();
        var btnOnScreen = isSaveBtnOnScreen();
        var floatingBtn = $('.floating-btn');
        if (btnOnScreen || interestsOnScreen) {
            floatingBtn.fadeOut('slow');
        } else {
            floatingBtn.fadeIn('slow');
        }
    }
    window.onscroll = function() { manageBtnView(); };

    $(function(){
        manageBtnView();
    });
</script>
