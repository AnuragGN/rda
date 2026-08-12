<?php
$footer = \App\Models\PerformanceData::getFooterNotes($forFund);
?>

<div style="font-size: 13px!important">
    <p>
        @foreach($footer['notes'] as $note)
            {{$note}}<br/>
        @endforeach
    </p>

    @foreach($footer['pairs'] as $key => $value)
        <p><b>{{$key}}</b><br>{{$value}}</p>
    @endforeach

</div>
