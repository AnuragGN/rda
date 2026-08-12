
<?php
$pvId = 'id-activity-values';
?>

<style>
    .row-value {
        display: flex;
        justify-content: space-between;
    }
    .row-value a.rv-uidl {
        font-size: 14px;
        font-weight: 600;
        color: black;
    }
    .row-value a.rv-udl {
        font-size: 16px;
        font-weight: 600;
        color: black;
    }
</style>
<div class="row">
    <div class="col-12">

        @foreach($activities['children'] as $i => $activity)
            @if($activity['type'] != 'pool')
                <div class="activity-values"style="display: block">
                    <div class="row-value">
                        @if( $activity['type'] == 'unique-direct-link')
                            <a href="{{ $activity['link'] }}" class="rv-udl"> {{ $activity['name'] }}</a>
                        @elseif( $activity['type'] == 'unique-indirect-link')
                            <span> {{ $activity['name'] }}
                                (<a href="{{ $activity['link'] }}" class="rv-uidl">{{ $activity['linkTitle'] }}</a>)
                            </span>
                        @else
                            <span>{{ $activity['name'] }}</span>
                        @endif
                        <span>${{ $activity['amount'] }}</span>
                    </div>


                </div>
            @else
                <div class="row-activity">
                    <div class="activity">
                        <p class="activity-name">
                            <a href="javascript:void(0);"
                               class="js_toggle_pool_values"
                               title="Expand / Minimize"
                               data-target-id="{{$pvId}}">
                                <small><i class="fas fa-minus-circle"></i><i class="fas fa-plus-circle hide"></i></small>
                            </a> {{ $activity['name'] }}</p>
                        <h5> ${{ $activity['amount'] }}</h5>
                    </div>
                    @if($activity['children'])
                    <div class="activity-values" id="{{$pvId}}" style="display: block">
                        @foreach($activity['children'] as $i => $child)
                                <div style="display: flex; justify-content: space-between">
                                    <span> {{ $child['name'] }}</span>
                                    <span>${{ $child['amount'] }}</span>
                                </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endif
        @endforeach

    </div>
</div>
