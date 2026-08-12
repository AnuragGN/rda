<?php
/** @var \App\Models\Contact $contact */
$contact = \App\Models\Contact::sessionContact();

if (\App\Models\ClientInfo::isGNA()) {
    /** @var \App\Models\ContactAddress $cAddress */
    $cAddress = $contact->getAnyAddress();
    $address = $cAddress->getMultiLineAddress();
} else {
    $type = \App\Models\AddressType::getContactAddressTypePrimary();
    $address = $contact->getMultiLineAddress($type);
}
?>

<header>
    <div class="header-box">
        <div class="left">
            <div><img src="{{ './' . \App\Models\ClientInfo::logo() }}" alt=""></div>
        </div>
        <div class="right">
            <div class="name">{!! $contact->name !!}</div>
            <div class="address">{!! $address !!}</div>
        </div>
    </div>
    <div style="clear: both;"></div>
    <hr>
</header>
