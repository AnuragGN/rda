<?php
if (!isset($search_only)) $search_only = false;
?>

@if($search_only)
    <div id="id_org_catalog_typeahead" class="catalog-ta-search mt-32 mb-3">
        <input id="id_org_name"
               type="text" class="form-control typeahead"
               placeholder="Enter name"
               aria-label="Enter name"
               aria-describedby="basic-addon2">
        <button class="btn btn-theme" type="button" onclick="onCatalogTypeAheadOrgSearch()"><i class="fas fa-search"></i></button>
    </div>
@else
    <div class="row">
        <div class="col-md-6 col-sm-6 mb-3">
            <a href="{{route('organizations-catalog')}}" class="browse-option">
                <div class="title">Browse by Organization Category</div>
                <div class="icon">
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 mb-3">
            <a href="{{route('programs-catalog')}}" class="browse-option">
                <div class="title">Browse by Program Category</div>
                <div class="icon">
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        </div>
    </div>

    <div class="catalog-ta-label">Search organizations by name</div>
    <div id="id_org_catalog_typeahead" class="catalog-ta-search mt-32 mb-3">
        <input id="id_org_name"
               type="text" class="form-control typeahead"
               placeholder="Enter name"
               aria-label="Enter name"
               aria-describedby="basic-addon2">
        <button class="btn btn-light org-search" type="button" onclick="onCatalogTypeAheadOrgSearch()"><i class="fas fa-search"></i></button>
    </div>
    {{--<hr>--}}
@endif

<script>
    function onCatalogTypeAheadOrgSelected(oid) {
        window.location.href = '/m/catalog/organization/' + oid;
    }
    function onCatalogTypeAheadOrgSearch(){
        var name = $('#id_org_name').val();
        console.log('name: ' + name);
        window.location.href = '/m/catalog/orgs/search?name=' + name;
    }
</script>

