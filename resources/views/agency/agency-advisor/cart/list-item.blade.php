<?php
$address = $model->getOrgAddress();
if (\App\Models\ClientInfo::isJCF()) {
    $type = $model->anonymous == "N" ? '' : '(Anonymous)';
} else {
    $type = $model->anonymous == "N" ? '(Non-anonymous)' : '(Anonymous)';
}

$date_format = \Carbon\Carbon::parse($model->created_on);
?>

<tr>
    <td style="text-align: left !important;">{{ $model->fund ? $model->fund->name : '-'}}</td>
    <td style="text-align: left !important;">{{ $model->getOrgName() }}</td>
    <td style="text-align: left !important;">{{ \App\Helpers\GnUtils::money($model->amount) }}</td>
    <td style="text-align: left !important;">{{ $model->getGrantFromName() }}</td>
    <td style="text-align: left !important;">{{ $date_format->format('d-m-Y') }}</td>
    <td style="text-align: left !important;">
        <a href="{{route('agency-cart-detail', $model->cart_id)}}" style="color:#fff;" class="btn btn-accent btn-sm">View</a>&nbsp;

        <a style="color:#fff;" class="btn btn-accent btn-sm" onclick="get_notification_popup({{ $model->cart_id }});">Send Notification </a>&nbsp;

        <a style="color:#fff;" class="btn btn-accent btn-sm" onclick="notification_logs({{ $model->cart_id }});">Logs</a></td>
    </tr>
