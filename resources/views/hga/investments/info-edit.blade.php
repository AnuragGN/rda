<p>Choose a donor-advised fund in the drop-down field below to view your current investment selections for that
    fund and make changes. Once you submit your changes, HighGround will be notified of the change request and
    will process a trade. You will be notified when the change request is received and when the investment
    trade has been completed.</p>
<p>Please select a maximum of two entries.</p>
<p>*Your requested investment changes will be reflected on your dashboard once the investment trade has been completed.</p>

<p style="display: none">
    @if(count($selector) > 1)
        Choose a fund in the drop-down field below to view the investment details.
    @else
        Investment selection for <b>{{ array_values($selector)[0] }}</b>
    @endif
</p>
