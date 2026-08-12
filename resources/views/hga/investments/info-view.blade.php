@if(count($selector) > 1)
    <p>Choose a donor-advised fund in the drop-down field below to view your investment selections for that fund.</p>
@else
    <p>Investment selection for <b>{{ array_values($selector)[0] }}</b></p>
@endif
