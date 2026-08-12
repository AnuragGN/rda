<?php
if (!isset($tooltipInfo)) $tooltipInfo = null;
// if (!isset($ttCls)) $ttCls = "col-md-1 text-center2";
if (!isset($ttCls)) $ttCls = "";
?>
@if($tooltipInfo)
    {{--<sup style="font-size: 1rem;" data-toggle="tooltip" data-placement="top" title="{{$tooltipInfo}}">--}}
        {{--<i class="fa fa-info-circle"></i>--}}
    {{--</sup>--}}

    <div class="{{$ttCls}}" style="max-width: 28px!important; padding: 0 6px;" data-toggle="tooltip" data-html="true" title="{{$tooltipInfo}}">
        <i class="fas fa-info-circle"></i>
    </div>
@endif
