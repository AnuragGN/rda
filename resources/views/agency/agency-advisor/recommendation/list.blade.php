<?php
$donorSession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($recommendation as $i => $recom)
    @include("agency.agency-advisor.recommendation.list-item", ['recom' => $recom, 'total' => count($recommendation), 'donorSession' => $donorSession])
@empty
    @include("utils.data-not-found", [])
@endforelse
