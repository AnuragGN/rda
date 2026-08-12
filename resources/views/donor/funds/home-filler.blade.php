<?php
if (\App\Models\ClientInfo::isGMF()) {
    $query = \App\Models\Organization::where(['allow_recommendation' => 'Y']);
} else {
    $query = \App\Models\Organization::where(['visible' => 'Y', 'allow_recommendation' => 'Y']);
}

// $orgs = $query->orderBy('name', 'ASC')->limit(3)->get();

$collection = [4244, 4053, 540, 6307, 5389, 7003, 2937, 4223, 1922, 5842, 6958, 5960, 4284, 472, 479, 3448, 6724];

shuffle($collection);
$ids = array_splice($collection, 0, 5);

$orgs = \App\Models\OrganizationInfo::whereIn('organization_id', $ids)->limit(3)->get();
$items = [];

/** @var \App\Models\Organization $org */
foreach($orgs as $org) {
    if (!$org->catalog_data) continue;
    $items[] = $org->getCatalogViewData();
}
?>

<div class="row">
    <div class="col-12">
        <h3 class="page-subtitle uppercase">Organizations</h3>
    </div>
</div>

@include('charity.browser', ['search_only' => true])

@include('charity.organization-list-items', ['items' => $items])
