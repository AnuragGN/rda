<?php
// MKF organization Id = 5746
$contact = \App\Models\Contact::sessionContact();
$funds = \App\Models\Fund::getSelectableForGrantRecommendation($contact->id);
?>

<style>
    .jconfirm, .jconfirm p {
        color: #000;
    }
    .jconfirm .jconfirm-box .jconfirm-buttons button {
        white-space: pre-wrap!important;
        min-width: 120px!important;
        text-transform: none!important;
        font-size: 1rem!important;
        font-weight: normal!important;
    }
    .jconfirm .jconfirm-box div.jconfirm-content-pane {
        max-height: 500px!important;
    }
    .jconfirm .jconfirm-box .jconfirm-buttons {
        display: flex!important;
    }
    .jconfirm .jconfirm-box .jconfirm-buttons button:not(:first-child) {
        margin-left: 1rem!important;
    }
    .mkf-fund-updated {
        font-weight: 600;
    }
    .mkf-fund-updated span {
        font-weight: 700;
    }
    .modal-mkf {
        max-width: 600px;
    }
    .modal-mkf .modal-header {
        padding-bottom: 0.5rem;

    }
</style>

{{--<button type="button" data-toggle="modal" data-target="#mkf-modal">Launch modal</button>--}}

<div id="mkf-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-mkf" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Grant Recommendation to Marjory Kaplan Foundation Fund</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body gn-form">

                <form id="id-mkf-grant-form">

                    <input type="hidden" name="mkf_amount" id="mkf_amount" value=""/>
                    <input type="hidden" name="mkf_grant" id="mkf_grant" value="">

                    <div class="form-group row">
                        {!! Form::label('mkf_fund_id', 'Fund', ['class' => 'col-sm-3 col-form-label bmd-label-floating']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('mkf_fund_id', $funds, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amountSelectionRadio" value="id_floating_amount" id="idr_floating_amount">
                        <label class="form-check-label" for="idr_floating_amount">Amount ($)</label>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-5">
                            <input id="id_floating_amount" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" step2="any" name="amount" type="number">
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="amountSelectionRadio" value="id_percentage" id="idr_percentage">
                        <label class="form-check-label" for="idr_percentage">
                            Percentage of your total grant recommendation ($<span id="id_amount_for_percentage">1200.00</span>)
                        </label>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-5">
                            <input id="id_percentage" class="form-control" name="amount" type="number">
                        </div>
                    </div>

                    <p class="mkf-fund-updated">Updated Total Grant Amount: $<span id="id-sum-of-grants">1200</span></p>

                    <div class="form-group row">
                        <label for="mkf_grant_purpose" class="col-sm-3 col-form-label text-right2 pr-0">Optional Designation</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="mkf_grant_purpose" cols="50" id="mkf_grant_purpose"></textarea>
                            <span class="from-footer hide">Purpose will be included on the Grant Check</span>
                        </div>
                    </div>

                    <div class="form-group row hide">
                        <label for="mkf_notes" class="col-sm-3 col-form-label text-right2 pr-0">Note</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="2" name="mkf_notes" cols="50" id="mkf_notes"></textarea>
                            <span class="from-footer">Internal note for Staff</span>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-4 col-5">
                            <input name="save" class="btn btn-accent w100" type="submit" value="Continue">
                        </div>
                        <div class="col-sm-4 col-5 text-right2">
                            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('utils.in-progress')

<script>
    function customAlert(message) {
        $.alert({
            title: '',
            content: message,
            buttons: {
                ok: {
                    text: 'OK',
                    btnClass: 'btn-theme',
                    keys: ['enter', 'shift'],
                    action: function(){}
                }
            }
        });
    }
    $('#id-mkf-grant-form').on('submit', function(e) {
        e.preventDefault();

        var amount = $('#mkf_amount').val();
        if (!amount || amount == 0) {
            customAlert('Please fill in Amount or Percentage');
            return;
        }
        if (amount < 5) {
            customAlert('Grant amount should not be less than 5 dollars');
            return;
        }

        $('#main_mkf_amount').val(amount);
        $('#main_mkf_fund_id').val($('#mkf_fund_id').val());
        $('#main_mkf_grant_purpose').val($('#mkf_grant_purpose').val());
        $('#main_mkf_notes').val($('#mkf_notes').val());

        continueCheckout();
    });
</script>

<script>
    function floatWithCommas(amount) {
        // parseFloat(Math.round(num3 * 100) / 100).toFixed(2);
        amount = parseFloat(amount).toFixed(2);
        var parts = amount.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

    function setMkfAmount(amount) {
        $("#mkf_amount").val(amount);
    }

    function setAmounts(mkf_amount) {
        var initial_amount = gGrantsTotal;
        var final_amount = parseFloat(initial_amount) + parseFloat(mkf_amount);

        console.log("initial_amount: " + initial_amount);
        console.log("mkf_amount: " + mkf_amount);
        console.log("final_amount: " + final_amount);

        var display_amount = floatWithCommas(final_amount);
        $("#id-sum-of-grants").html(display_amount);
        $("#final_amount").val(final_amount);
        $("#mkf_amount").val(mkf_amount);
    }

    $("#id_percentage").keyup(function() {
        // get amount and input
        var percentage = $('#id_percentage').val();
        if (!percentage || percentage == '') percentage = 0;
        console.log("percentage: " + percentage);

        if (percentage == 0) return;
        
        // manage radios
        $('#id_floating_amount').val('');
        $("#idr_floating_amount").prop("checked", false);
        $("#idr_percentage").prop('checked', true);

        var additional_amount = (gGrantsTotal/100) * percentage;
        if (!additional_amount || additional_amount == '' || additional_amount < 0) additional_amount = 0;

        setAmounts(additional_amount);
    });

    //$("input").keydown(function(){
    //  $("input").css("background-color", "yellow");
    //});
    $("#id_floating_amount").keyup(function() {
        // manage radios
        $("#id_percentage").val('');
        $("#idr_percentage").prop('checked', false);
        $("#idr_floating_amount").prop("checked", true);

        // get input amount
        var additional_amount = $('#id_floating_amount').val();
        if (!additional_amount || additional_amount == '') additional_amount = 0;

        setAmounts(additional_amount);
    });

    // var selection = $( "input:radio[name=amountSelectionRadio]" );
    // selection.on( "change", function() {
    //    console.log( "selection: " +  );
    //    alert( "selection: " + $(this).val() );
    // });

</script>

<script>
    $('#js_confirm_grants').on('click',function(){

        if (gGrantsTotal == 0) {
            alert("Please select one or more Grants to continue.");
        } else {
            $('').html(floatWithCommas(gGrantsTotal));

            $("#id_percentage").val('');
            $('#id_floating_amount').val('');
            $('#id_amount_for_percentage').html(floatWithCommas(gGrantsTotal));

            setAmounts(0);
            showDialog();
            console.log("JCF Grant total is :" + gGrantsTotal);
        }
        return false;
    });

    function showDialog() {
        var message = "<p>Would you like to make an additional grant recommendation to " +
                "the Marjory Kaplan Foundation Fund and help support the Foundation's ongoing programs? </p>";

        $.confirm({
            columnClass: 'medium',
            title: '',
            content: message,
            buttons: {
                yes: {
                    text: 'Yes, I want to give to JCF’s endowment',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function(){
                        $('#mkf-modal').modal('show'); // show MKF modal
                    }
                },
                no: {
                    text: 'No, not at this time',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function(){
                        continueCheckout(); // conitnue checkout
                    }
                }
            }
        });
    }

    function continueCheckout() {
        $('#mkf-modal').modal('hide');
        showInProgressOverlay();
        $('#id_form_checkout').submit();
    }
</script>

