
<p class="mb-1"><i>Contributions to your Fund have the potential to grow, so you can give more to the organizations you love.</i></p>
<p class="mb-1"> <a href="/ma/docs/nif/NIF-investment-strategies.pdf" style="text-decoration: underline" target="_blank">Click here</a> to learn more about the investment strategies available to grow your Donor Advised Fund.</p>
<p>DAFs holding $10,000 or more are eligible to invest.* All amounts not invested will remain in a cash account. Due to market fluctuations, we recommend keeping one year or more of anticipated grants in cash.</p>

<p>You can select a maximum of 6 investment options.
    <br>Select an asset pool by entering a value from 1 to 100.
    <br>Values must total up to 100%.</p>

<p>
    @if(count($selector) > 1)
        Choose a fund in the drop-down field below to view the investment details.
    @else
        Investment selection for <b>{{ array_values($selector)[0] }}</b>
    @endif

    @include('nif.investments.info-footnote-requested')
</p>
