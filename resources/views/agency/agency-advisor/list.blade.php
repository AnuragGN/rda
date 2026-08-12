<?php
$donorSession = \App\Helpers\GnUtils::isDonorSession();
?>

@forelse($funds as $i => $fund)
    @include("donor.funds.list-item", ['fund' => $fund, 'total' => count($funds), 'donorSession' => $donorSession])
@empty
    @include("utils.data-not-found", [])
@endforelse
