<?php
$assetServer = \App\Models\ClientConfig::assetServer();
if (!isset($interestBased)) $interestBased = false;

$models = \App\Models\Content::getPromoArticles($interestBased);

$cls = isset($class)? $class : 'promo-box';
$classTitle = isset($classTitle) ? $classTitle : '';
?>

<h3 class="page-subtitle mt-2 {{$cls}} {{$classTitle}}">
    {{\App\Models\ClientInfo::isHGA() ? "NEWS" : "Articles"}}
</h3>

@foreach($models as $model)
    <div class="content-promo {{$cls}}">
        <div data-href="{{route('content.show', $model->content_id)}}" class="abstract" onclick="showArticle(event, this)">
            {!! $model->abstract !!}
        </div>
    </div>
@endforeach

<script>
    var assetServer = '{{$assetServer}}';
    console.log("assetServer: " + assetServer);

    $(".content-promo img" ).each(function( index ) {
        updateSrc(this);
    });
    $(".content-promo input" ).each(function( index ) {
        updateSrc(this);
    });
    function updateSrc(item) {
        var src = $(item).attr('src');
        console.log("src: " + src);
        if (src && src.startsWith('/')) {
            item.src = assetServer + src.substring(1);
        } else if (src && src.indexOf('images') != -1 && src.indexOf('http') == -1){
            item.src = assetServer + src;
        }
        console.log("item.src : " + item.src);
    }
    function showArticle(event, item) {
        var url = $(item).data('href');
        if ( event.ctrlKey ) {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    }
</script>
