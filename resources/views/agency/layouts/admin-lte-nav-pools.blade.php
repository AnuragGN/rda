<?php
$pools = \App\Models\PerformanceData::getPoolTabs();
?>

<li id="id_main_side_menu" class="nav-item has-treeview mt-2 {{ request()->is('m/agency/pool-performance*') ? 'menu-is-opening menu-open' : '' }}">

    <a href="#" class="nav-link nav-header2 {{ request()->is('m/agency/performance*') ? 'active' : '' }}">
        <p>Pool Performance<i class="fas fa-angle-right right"></i></p>
    </a>

    <ul class="nav nav-treeview">
        @foreach($pools as $pool)
            <li class="nav-item">
                <a href="{{route('agency-pool-performance', ['id'=>$pool['segment_id']])}}" class="nav-link">
                    <i class="nav-icon fas fa-chart-area"></i>
                    <p>{{$pool['segment_label']}}</p>
                </a>
            </li>
        @endforeach
    </ul>

</li>

<script>
    $(function(){
        highlighttabs("#id_main_side_menu", null)
    });
</script>
