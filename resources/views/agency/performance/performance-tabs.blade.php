<?php
$pools = [];
if (\App\Helpers\GnUtils::isDonorSession()) {
    $pools = \App\Models\GhSegment::getPoolTabs();
}
?>

<div class="tabs_oval" id="id_tabs_performance">
    <ul class="nav nav-tabs">
        @foreach($pools as $pool)
            <li class="nav-item">
                <a class="nav-link" href="{{route('donor-pool-performance', ['id'=>$pool->segment_id])}}">{{$pool->segment_label}}</a>
            </li>
        @endforeach
    </ul>
</div>

<script>
    $(function(){
        highlighttabs("#id_tabs_performance", "#id_performance_default");
    });
</script>