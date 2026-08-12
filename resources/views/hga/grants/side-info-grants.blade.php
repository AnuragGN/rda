<?php
$models = App\Models\GrantHistory::getRecentGrantRecommendations(10);
?>

<h3 class="page-subtitle uppercase mt-2">Recent Grants</h3>

@include('donor.grants.list', ['showRepeat' => true, 'models' => $models, 'sidepane' => true])