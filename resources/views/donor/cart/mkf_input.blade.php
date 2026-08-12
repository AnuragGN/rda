<?php
if (!isset($model) || !$model) {
    $model = new \App\Models\GrantItem();
}
?>
@if(\App\Models\ClientInfo::isJCF())

    {{-- for Marjory Kaplan Fund --}}
    <input type="hidden" name="mkf[amount]" id="main_mkf_amount" value={{$model->amount}}>
    <input type="hidden" name="mkf[fund_id]" id="main_mkf_fund_id" value={{$model->fund_id}}>
    <input type="hidden" name="mkf[grant_purpose]" id="main_mkf_grant_purpose" value="{{$model->grant_purpose}}">
    <input type="hidden" name="mkf[notes]" id="main_mkf_notes" value="{{$model->notes}}">

@endif