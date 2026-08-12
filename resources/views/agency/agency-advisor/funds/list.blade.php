<?php
$donorSession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($funds as $i => $fund)
    @include("agency.agency-advisor.funds.list-item", ['fund' => $fund, 'total' => count($funds), 'donorSession' => $donorSession])
@empty
    @include("utils.data-not-found", [])
@endforelse
