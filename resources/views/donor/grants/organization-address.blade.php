<?php
/** @var \App\Organization $organization */
// $address = $organization->getPrimaryAddress();
?>

<div class="form-group row">
    <label for="organization_name" class="col-sm-3 col-form-label text-right pr-0">Organization</label>
    <div class="col-sm-9">
        <div class="org-name">{{ $model->getOrgName() }}</div>
        <div class="org-address">{!! $model->getOrgAddress()->getAddressInline() !!}</div>
    </div>
</div>
