
<div class="row">
    <div class="col-12">
        <h2 class="page-subtitle">
            @if(isset($link))
                <a href="{{$link}}">{{$title}}</a>
            @else
                {{$title}}
            @endif
            <a href="javascript:void(0);" class="btn btn-accent btn-sm" data-child-id="id_browser" onclick="sageCollapsible(this)"><i class="fas fa-search"></i></a>
        </h2>
    </div>
</div>

<div id="id_browser" style="display: none">
    @include('charity.browser', ['search_only2' => true])
</div>
