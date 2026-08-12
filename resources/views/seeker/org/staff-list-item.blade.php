<?php
$contact = $orgContact->getContact();
$contactName = $contact ? $contact->getFullnameAttribute() : '';
$receiveEmail = $contact && $contact->receive_email && $contact->receive_email == 'Y' ? true : false;
$status = $orgContact->status ? $orgContact->status : '';
$defaultContact = $orgContact->is_default == 'Y' ? true : false;
$contactId = $orgContact->contact_id;
$accessLevel = $orgContact->access_level;
$orgId = $orgContact->organization_id;
$changeStatusText = 'activate';
if ($status == constant('App\Models\OrganizationContact::STATUS_APPROVED')) {
	$changeStatusText = 'deactivate';
}
?>
<tr>
	<td>
		<div class="form-check">
			<input class="form-check-input js-access-level-type" type="radio" name="user_<?=$contactId?>" 
			value="{{constant('App\Models\OrganizationContact::ACCESS_LEVEL_TYPE_ADMIN')}}"
			data-contactId="<?=$contactId?>" data-organizationId="<?=$orgId?>"
			<?php if ($accessLevel == constant('App\Models\OrganizationContact::ACCESS_LEVEL_TYPE_ADMIN')) { echo 'checked';} ?> >
			<label class="form-check-label">admin</label>
		</div>
		<div class="form-check">
			<input class="form-check-input js-access-level-type" type="radio" name="user_<?=$contactId?>"
			value="{{constant('App\Models\OrganizationContact::ACCESS_LEVEL_TYPE_STAFF')}}" 
			data-contactId="<?=$contactId?>" data-organizationId="<?=$orgId?>"
			<?php if ($accessLevel == constant('App\Models\OrganizationContact::ACCESS_LEVEL_TYPE_STAFF')) { echo 'checked';} ?> >
			<label class="form-check-label">staff</label>
		</div>
	</td>
	<td>
	<a href="{{route('gs-account-edit-profile', ['id' => $contactId])}}">
			{{ $contactName }}
	</a>
	</td>
	<td>
		<input type="radio" class="js-default-contact" name="default_contact" value="<?=$contactId?>"  data-organizationId="<?=$orgId?>" <?php if ($defaultContact) { echo 'checked';} ?> >
	</td>
	<td>
		<input type="checkbox" class="js-receive-email" name="receive_email" data-contactId="<?=$contactId?>" <?php if ($receiveEmail) { echo 'checked';} ?> >
	</td>
	<td>
		<a id="<?=$contactId?>_status_link" href="javascript:void(0)" class="js-contact-status-update" data-contactId="<?=$contactId?>" data-organizationId="<?=$orgId?>" >{{ $changeStatusText }}</a>
	</td>
</tr>
									

