@extends ('agency.layouts.main')

@section ('content')

    @if(\Illuminate\Support\Facades\App::environment('prod'))
        <script type="text/javascript" src="https://js.authorize.net/v3/AcceptUI.js" charset="utf-8"> </script>
    @else
        <script type="text/javascript" src="https://jstest.authorize.net/v3/AcceptUI.js" charset="utf-8"></script>
    @endif

    <script type="text/javascript" src="/ma/javascripts/payment.js" charset="utf-8"></script>


    <h1>
        Sandbox Payment
    </h1>

    <form id="paymentForm"
          method="POST"
          action="/m/authorize-net/make-payment">
        @csrf
        <input type="hidden" name="dataValue" id="dataValue" />
        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
        <button type="button"
                class="AcceptUI"
                data-billingAddressOptions='{"show":true, "required":true}'
                data-apiLoginID="9U7yvp9YVE47"
                data-clientKey="3ZaL3u5X68kSm3BKBfx6SkWNvRFmkRztV24HaXrrK32e3qt87dT4qv9wF8rF9zdD"
                data-acceptUIFormBtnTxt="Submit"
                data-acceptUIFormHeaderTxt="Card Information"
                data-paymentOptions='{"showCreditCard": true, "showBankAccount": false}'
                data-responseHandler="responseHandler">Add Money to your DAF
        </button>
    </form>

@endsection

@if(false)
    {{--payment nonce == opaqueData.dataValue --}}
    {
    "opaqueData": {
    "dataDescriptor": "COMMON.ACCEPT.INAPP.PAYMENT",
    "dataValue": "eyJjb2RlIjoiNTBfMl8wNjAwMDUzNUE1OTkzREQ1NEM1NzY0OTgwNTZDQzY5MEVBRjY5MTU3RjBEQThEMjU2N0EyQkUwMUNBNzQ5QkY0ODRDMTgyMjRGN0IzMEE4REI2MUJDQUI1NDMxMTZCNzkwOUM3OUMwIiwidG9rZW4iOiI5NTA3OTE3MzE1NTg5OTYzOTA0NjA0IiwidiI6IjEuMSJ9"
    },
    "messages": {
    "resultCode": "Ok",
    "message": [{
    "code": "I_WC_01",
    "text": "Successful."
    }
    ]
    },
    "encryptedCardData": {
    "cardNumber": "XXXXXXXXXXXX1111",
    "expDate": "12/22",
    "bin": "411111"
    },
    "customerInformation": {
    "Ellen": "",
    "Johnson": ""
    }
    }
@endif
