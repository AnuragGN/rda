<?php
$titleInfo = null;
if (\App\Models\ClientInfo::isJCF() || \App\Models\ClientInfo::isGNA()) {
    $titleInfo = 'The Charitable Catalog’s content is populated and maintained by each organization. Organization participation in the catalog is completely voluntary';
}
?>

@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->CHARITABLE_CATALOG, 'titleInfo' => $titleInfo])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-8 col-r-15">

                        @include('charity.browser')

                        <h3 class="page-subtitle uppercase">Organizations </h3>

                        @include('donor.common.infinite-scroll')

                    </div>

                    <div class="col-lg-4 col-l-15">
                        @include('pane-placeholder', ['classTitle' => 'mt-2'])
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            jsInfiniteScroll.init('/m/catalog/orgs/ajax');
            jsInfiniteScroll.runLoadData();
        });
    </script>

@endsection

