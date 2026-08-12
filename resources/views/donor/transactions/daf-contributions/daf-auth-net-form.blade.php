
<form id="paymentForm"
      method="POST"
      action="/m/daf/authorize-net/contribution-cc">

    {{-- security token --}}
    {{ csrf_field() }}

    {{-- custom fields --}}
    <input type="hidden" name="payAmount" id="id_pay_amount" />
    <input type="hidden" name="payTargetType" id="id_pay_target_type" />
    <input type="hidden" name="payTargetId" id="id_pay_target_id" />
    <input type="hidden" name="payContactId" id="id_pay_contact_id" />
    <input type="hidden" name="payNote" id="id_pay_note" />

    {{-- authorize.net fields --}}
    <input type="hidden" name="dataValue" id="id_data_value" />
    <input type="hidden" name="dataDescriptor" id="id_data_descriptor" />

    <button type="button"
            class="AcceptUI hide"
            data-billingAddressOptions='{"show":true, "required":true}'
            data-apiLoginID="{{env('MERCHANT_LOGIN_ID')}}"
            data-clientKey="{{env('MERCHANT_PUBLIC_CLIENT_KEY')}}"
            data-acceptUIFormBtnTxt="Submit"
            data-acceptUIFormHeaderTxt=""
            data-paymentOptions='{"cardCodeRequired": true, "showCreditCard": true, "showBankAccount": true}'
            data-responseHandler="responseHandler">Add Money to your DAF
    </button>

</form>

@if(\Illuminate\Support\Facades\App::environment('prod'))
    <script type="text/javascript" src="https://js.authorize.net/v3/AcceptUI.js" charset="utf-8"> </script>
@else
    <script type="text/javascript" src="https://jstest.authorize.net/v3/AcceptUI.js" charset="utf-8"></script>
@endif

@include('donor.transactions.modal-in-progress')

<script>
    function responseHandler(response) {
        if (response.messages.resultCode === "Error") {
            var i = 0;
            while (i < response.messages.message.length) {
                alert(response.messages.message[i].text + "(" + response.messages.message[i].code + ")");
                console.log(
                        response.messages.message[i].code + ": " +
                        response.messages.message[i].text
                );
                i = i + 1;
            }
        } else {
            // console.log(response);
            paymentFormUpdate(response.opaqueData);
        }
    }

    function paymentFormUpdate(opaqueData) {
        document.getElementById("id_data_descriptor").value = opaqueData.dataDescriptor;
        document.getElementById("id_data_value").value = opaqueData.dataValue;
        clearForm();
        document.getElementById("paymentForm").submit();

        // show modal
        $('#id_transaction_in_progress').modal('show')
    }

    function clearForm() {
        // var doc = document.getElementById('myframe1').contentWindow.document.getElementById('x');
    }
</script>
