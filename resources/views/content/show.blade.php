<?php
$assetServer = \App\Models\ClientConfig::assetServer();
?>

@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => ''])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8  col-r-15 content-page">
                        <h2 class="page-subtitle uppercase">{{$model->title}}</h2>
                        {!! $model->content_text !!}
                    </div>

                    <div class="col-lg-4 col-l-15">
                        @include('pane-placeholder', ['class' => ''])
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var assetServer = '{{$assetServer}}';
        console.log("assetServer: " + assetServer);

        $(".content-page img" ).each(function( index ) {
            updateSrc(this);
        });
        $(".content-page input" ).each(function( index ) {
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
    </script>

@endsection
