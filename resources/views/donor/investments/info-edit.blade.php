<p>You can select a maximum of 6 investment options.
    <br>Select an asset pool by entering a value from 1 to 100.
    <br>Values must total up to 100%.</p>

<p>
    @if(count($selector) > 1)
        Choose a fund in the drop-down field below to view the investment details.
    @else
        Investment selection for <b>{{ array_values($selector)[0] }}</b>
    @endif
</p>
