{{--Product--}}

<div class="form-group row">
    {!! Form::label('grant_purpose', 'Purpose', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('grant_purpose', null, ['class' => 'form-control', 'rows' => 2]) !!}
        <span class="from-footer">Purpose will be included on the grant check</span>
    </div>
</div>

<div class="form-group row" style="margin-bottom: 0">
    {!! Form::label('notes', 'Note', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2]) !!}
        <span class="from-footer">Internal note for staff</span>
    </div>
</div>

<div class="form-group">
    <div class="row">
        {!! Form::label('anonymous', 'Donor Identity', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
        <div class="col-sm-9" style="padding: 6px 8px;">
            {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x ml-1', 'id' => 'id_anonymous']) !!} &nbsp; Anonymous
        </div>
    </div>

    <div class="row" id="id_from_contact_box" style="display: none">
    </div>
</div>


<script>
    function onShowSelectFromDonor() {
        $('#id_from_contact_box').show(400);
    }
    function onHideSelectFromDonor() {
        $('#id_from_contact_box').hide(400);
    }

    $(function(){
        // 1. anonymous
        var itemAnonymous = document.getElementById('id_anonymous');
        if (itemAnonymous.checked){
            onHideSelectFromDonor();
        } else {
            onShowSelectFromDonor();
        }

        itemAnonymous.addEventListener('change', function(event) {
            if (event.currentTarget.checked) {
                onHideSelectFromDonor();
            } else {
                onShowSelectFromDonor();
            }
        });

        // 2. fund id
        var itemFundId = $('#id_fund_id');

        var fundId = itemFundId.val();
        onFundIdChanged(fundId);

        itemFundId.change(function() {
            fundId = $(this).val();
            onFundIdChanged(fundId);
        });

    });

    var varFromContactId = {!! json_encode($fromContactId) !!};

    function onFundIdChanged(fundId){

        var url = '/m/ajax/make-a-grant/select-grant-from?fund_id=' + fundId + '&from_contact_id=' + varFromContactId;
        $("body").css("cursor", "progress");

        $.ajax({
            url: url,
            dataType: 'json',
            method: 'get'
        }).done(function (data) {
            if (!data || data.status != 200) {
                if (data.mesg) showAlert('Error', data.mesg);
                else showAlert('Error', "Your request could not be processed!");
            } else {
                $('#id_from_contact_box').html(data.html);
            }
            $("body").css("cursor", "default");
        }).fail(function(){
            showAlert('Failed', "Your request could not be processed!");
            $("body").css("cursor", "default");
        });

    }

</script>

