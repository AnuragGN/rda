@if(count($selector) > 1)
    <p>Choose a fund in the drop-down field below to view the investment details.</p>
@else
    <p>Investment selection for <b>{{ array_values($selector)[0] }}</b></p>
@endif
